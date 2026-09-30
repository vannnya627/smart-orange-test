<?php

declare(strict_types=1);

namespace App\Generator;

use Generator;
use RuntimeException;
use XMLReader;
use ZipArchive;

final readonly class XLSXGenerator
{
    public function run(string $filePath): Generator
    {
        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            throw new RuntimeException("Не вдалося відкрити XLSX архів: {$filePath}");
        }

        // таблиця рядків  - sharedStrings.xml
        $sharedStrings = [];
        $ssXml = new XMLReader;
        if ($ssXml->open("zip://{$filePath}#xl/sharedStrings.xml")) {
            while ($ssXml->read()) {
                if ($ssXml->nodeType === XMLReader::ELEMENT && $ssXml->name === 'si') {
                    $node = simplexml_load_string($ssXml->readOuterXml());
                    $text = '';
                    if (isset($node->t)) {
                        $text = (string) $node->t;
                    } elseif (isset($node->r)) {
                        foreach ($node->r as $r) {
                            $text .= $r->t;
                        }
                    }
                    $sharedStrings[] = $text;
                }
            }
            $ssXml->close();
        }

        // лист sheet1.xml
        $sheetXml = new XMLReader;
        if (! $sheetXml->open("zip://{$filePath}#xl/worksheets/sheet1.xml")) {
            $zip->close();
            throw new RuntimeException('Не вдалося відкрити лист sheet1.xml');
        }

        try {
            $headers = [];
            $isFirstRow = true;

            while ($sheetXml->read()) {
                if ($sheetXml->nodeType === XMLReader::ELEMENT && $sheetXml->name === 'row') {
                    $rowXml = simplexml_load_string($sheetXml->readOuterXml());
                    if ($rowXml === false) {
                        continue;
                    }

                    $rowData = [];

                    foreach ($rowXml->c as $cell) {
                        $ref = (string) $cell['r'];
                        $col = (string) preg_replace('/[0-9]/', '', $ref);
                        $type = (string) $cell['t'];
                        $val = (string) $cell->v;

                        if ($type === 's') {
                            $val = $sharedStrings[(int) $val] ?? '';
                        } elseif (isset($cell->f) && $val === '') {
                            $val = (string) $cell->f;
                        }

                        $rowData[$col] = $val;
                    }

                    if ($isFirstRow) {
                        $headers = $rowData;
                        $isFirstRow = false;
                        unset($rowXml);

                        continue;
                    }

                    $item = [];
                    foreach ($headers as $col => $headerName) {
                        $item[$headerName] = $rowData[$col] ?? null;
                    }

                    yield $item;

                    unset($rowXml, $rowData, $item);
                }
            }
        } finally {
            $sheetXml->close();
            $zip->close();
        }
    }
}
