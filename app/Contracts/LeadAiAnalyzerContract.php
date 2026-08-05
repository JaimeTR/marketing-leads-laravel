<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\LeadInsightData;
use App\Models\Lead;
use Illuminate\Support\Collection;

interface LeadAiAnalyzerContract
{
    public function analyzeLead(Lead $lead): LeadInsightData;

    public function summarizeDashboard(Collection $leads, array $metrics): array;

    public function predict(Collection $leads, array $metrics): array;
}
