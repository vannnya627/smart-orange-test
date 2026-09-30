<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface LeadRepositoryInterface
{
    /**
     * @param  array<string, array<string, mixed>>  $batch
     */
    public function upsertBatch(array $batch): void;
}
