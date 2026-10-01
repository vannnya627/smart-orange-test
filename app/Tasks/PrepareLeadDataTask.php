<?php

declare(strict_types=1);

namespace App\Tasks;

use Illuminate\Support\Carbon;

final readonly class PrepareLeadDataTask
{
    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>|null
     */
    public function run(array $item): ?array
    {
        $extId = $item['external_id'] ?? null;
        if (! $extId) {
            return null;
        }

        $now = now()->format('Y-m-d H:i:s');

        return [
            'external_id' => $extId,
            'first_name' => $item['first_name'] ?: null,
            'last_name' => $item['last_name'] ?: null,
            'phone' => ! empty($item['phone']) && is_scalar($item['phone']) ? ltrim((string) $item['phone'], '=+') : null,
            'email' => $item['email'] ?: null,
            'city' => $item['city'] ?: null,
            'source' => $item['source'] ?: null,
            'utm_campaign' => $item['utm_campaign'] ?: null,
            'product' => $item['product'] ?: null,
            'budget_uah' => is_numeric($item['budget_uah'] ?? null) ? (float) $item['budget_uah'] : null,
            'status' => $item['status'] ?: null,
            'manager' => $item['manager'] ?: null,
            'comment' => $item['comment'] ?: null,
            'next_contact_at' => $this->parseExcelDate($item['next_contact_at'] ?? null),
            'created_at' => $this->parseExcelDate($item['created_at'] ?? null) ?? $now,
            'updated_at' => $now,
        ];
    }

    private function parseExcelDate(mixed $value): ?string
    {
        if (empty($value) || ! is_scalar($value)) {
            return null;
        }

        if (is_numeric($value)) {
            $timestamp = (int) round(((float) $value - 25569) * 86400);

            return gmdate('Y-m-d H:i:s', $timestamp);
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }
}
