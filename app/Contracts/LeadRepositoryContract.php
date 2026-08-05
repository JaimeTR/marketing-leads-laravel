<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface LeadRepositoryContract
{
    public function paginate(array $filters = []): LengthAwarePaginator;

    public function create(array $data): Lead;

    public function createMany(array $rows): int;

    public function potentialDuplicates(array $data, ?int $excludeId = null): Collection;

    public function groupedDuplicates(): Collection;

    public function metrics(): array;

    public function recentForAnalysis(int $limit = 50): Collection;
}
