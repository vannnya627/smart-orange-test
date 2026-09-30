<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface LeadRepositoryInterface
{
    public function upsertBatch(array $batch): void;
}
