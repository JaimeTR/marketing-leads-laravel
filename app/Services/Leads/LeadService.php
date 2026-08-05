<?php

declare(strict_types=1);

namespace App\Services\Leads;

use App\Contracts\LeadAiAnalyzerContract;
use App\Contracts\LeadRepositoryContract;
use App\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeadService
{
    public function __construct(
        private readonly LeadRepositoryContract $repository,
        private readonly LeadAiAnalyzerContract $analyzer,
    ) {
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginate($filters);
    }

    public function create(array $data, bool $analyze = true): Lead
    {
        $lead = $this->repository->create($data);

        if ($analyze) {
            $insight = $this->analyzer->analyzeLead($lead);

            $lead->forceFill([
                'ai_score' => $insight->score,
                'ai_temperature' => $insight->temperature,
                'ai_summary' => $insight->summary,
                'ai_recommended_action' => $insight->recommendedAction,
                'ai_analyzed_at' => now(),
            ])->save();
        }

        return $lead->refresh();
    }

    public function dashboard(): array
    {
        $metrics = $this->repository->metrics();
        $recent = $this->repository->recentForAnalysis();

        return [
            'metrics' => $metrics,
            'insights' => $this->analyzer->summarizeDashboard($recent, $metrics),
            'predictions' => $this->analyzer->predict($recent, $metrics),
            'recent' => $recent,
        ];
    }

    public function checkDuplicates(array $data, ?int $excludeId = null): Collection
    {
        return $this->repository->potentialDuplicates($data, $excludeId);
    }

    public function duplicates(): Collection
    {
        return $this->repository->groupedDuplicates();
    }

    public function import(array $rows): array
    {
        $created = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $validation = Validator::make($row, [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email:rfc', 'max:120', Rule::unique('leads', 'email')],
                'company' => ['nullable', 'string', 'max:120'],
                'phone' => ['nullable', 'string', 'max:30'],
                'campaign' => ['required', 'string', 'max:100'],
            ]);

            if ($validation->fails()) {
                $errors[] = ['row' => $index + 2, 'errors' => $validation->errors()->toArray()];
                continue;
            }

            $this->repository->create($validation->validated());
            $created++;
        }

        return [
            'created' => $created,
            'errors' => $errors,
        ];
    }

    public function chatContext(): array
    {
        $dashboard = $this->dashboard();

        return [
            'metrics' => $dashboard['metrics'],
            'insights' => $dashboard['insights'],
            'predictions' => $dashboard['predictions'],
            'recent_leads' => $dashboard['recent']->take(10)->map(fn (Lead $lead) => [
                'name' => $lead->name,
                'email' => $lead->email,
                'company' => $lead->company,
                'campaign' => $lead->campaign,
                'source' => $lead->source?->source_name,
                'score' => $lead->ai_score,
                'temperature' => $lead->ai_temperature?->value,
                'created_at' => $lead->created_at?->toDateTimeString(),
            ])->values(),
        ];
    }
}
