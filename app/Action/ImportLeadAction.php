<?php

declare(strict_types=1);

namespace App\Action;

use App\Generator\XLSXGenerator;
use App\Tasks\PrepareLeadDataTask;
use App\Tasks\UpsertLeadBatchTask;

final readonly class ImportLeadAction
{
    private const int CHUNK_SIZE = 2000;

    public function __construct(
        private XLSXGenerator $generator,
        private PrepareLeadDataTask $prepareLeadDataTask,
        private UpsertLeadBatchTask $upsertLeadBatchTask,
    ) {}

    public function run(string $fullPath): void
    {
        $batch = [];
        foreach ($this->generator->run($fullPath) as $item) {
            $leadData = $this->prepareLeadDataTask->run($item);

            if ($leadData === null) {
                continue;
            }

            /** @var array<string, array<string, ?mixed>> $batch */
            $batch[$leadData['external_id']] = $leadData;

            if (count($batch) >= self::CHUNK_SIZE) {
                $this->upsertLeadBatchTask->run($batch);
                $batch = [];
            }
        }

        if (! empty($batch)) {
            $this->upsertLeadBatchTask->run($batch);
            $batch = null;
        }
    }
}
