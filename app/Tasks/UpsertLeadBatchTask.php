<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Repositories\Interfaces\LeadRepositoryInterface;

final readonly class UpsertLeadBatchTask
{
    public function __construct(
        private LeadRepositoryInterface $leadRepository,
    ) {}

    public function run(array $batch): void
    {
        $this->leadRepository->upsertBatch($batch);
    }
}
