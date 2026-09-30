<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Lead;
use App\Repositories\Interfaces\LeadRepositoryInterface;

final readonly class LeadRepository implements LeadRepositoryInterface
{
    /**
     * @param  array<string, array<string, mixed>>  $batch
     */
    public function upsertBatch(array $batch): void
    {
        Lead::query()->upsert(
            array_values($batch),
            ['external_id'],
            [
                'first_name', 'last_name', 'phone', 'email', 'city',
                'source', 'utm_campaign', 'product', 'budget_uah',
                'status', 'manager', 'comment', 'next_contact_at', 'updated_at',
            ]
        );
    }
}
