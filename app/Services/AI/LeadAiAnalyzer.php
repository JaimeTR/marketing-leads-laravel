<?php

declare(strict_types=1);

namespace App\Services\AI;

use App\Contracts\LeadAiAnalyzerContract;
use App\Data\LeadInsightData;
use App\Models\Lead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class LeadAiAnalyzer implements LeadAiAnalyzerContract
{
    public function __construct(
        private readonly LeadPromptFactory $prompts,
        private readonly OpenAiCompatibleClient $openAiCompatible,
        private readonly GeminiClient $gemini,
    ) {
    }

    public function analyzeLead(Lead $lead): LeadInsightData
    {
        try {
            $payload = $this->client()->complete($this->prompts->leadScorePrompt($lead));

            if ($payload !== []) {
                return LeadInsightData::fromArray($payload);
            }
        } catch (Throwable $exception) {
            Log::warning('AI lead analysis failed', ['message' => $exception->getMessage()]);
        }

        return $this->fallbackLeadScore($lead);
    }

    public function summarizeDashboard(Collection $leads, array $metrics): array
    {
        try {
            $payload = $this->client()->complete($this->prompts->dashboardPrompt($leads, $metrics));

            if ($payload !== []) {
                return $this->normalizeDashboardInsights($payload);
            }
        } catch (Throwable $exception) {
            Log::warning('AI dashboard summary failed', ['message' => $exception->getMessage()]);
        }

        return $this->normalizeDashboardInsights([
            'summary' => 'El dashboard usa reglas locales porque el proveedor de IA no esta configurado.',
            'trends' => ['Revisar campanas con mayor volumen y leads calientes.'],
            'recommendations' => ['Contactar primero leads con score alto.'],
            'risks' => ['Sin API key no se generan insights avanzados.'],
        ]);
    }

    public function predict(Collection $leads, array $metrics): array
    {
        try {
            $payload = $this->client()->complete($this->prompts->predictPrompt($leads, $metrics));

            if ($payload !== []) {
                return [
                    'predictions' => $this->stringList($payload['predictions'] ?? $payload),
                    'generated_at' => now()->toDateTimeString(),
                ];
            }
        } catch (Throwable $exception) {
            Log::warning('AI lead predictions failed', ['message' => $exception->getMessage()]);
        }

        return [
            'predictions' => [
                'Los leads calientes representan el mayor ingreso potencial: priorizarlos.',
                'Revisar las campanas con mayor volumen para ajustar presupuesto.',
                'Seguimiento dentro de 24 horas maximiza la conversion de leads recientes.',
            ],
            'generated_at' => now()->toDateTimeString(),
        ];
    }

    private function client(): OpenAiCompatibleClient|GeminiClient
    {
        return config('ai.default') === 'gemini'
            ? $this->gemini
            : $this->openAiCompatible;
    }

    private function normalizeDashboardInsights(array $payload): array
    {
        $summary = $payload['summary'] ?? '';

        if (is_array($summary)) {
            $summary = json_encode($summary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: 'Resumen no disponible.';
        }

        if (!is_string($summary) || $summary === '') {
            $summary = 'Resumen no disponible.';
        }

        return [
            'summary' => $summary,
            'trends' => $this->stringList($payload['trends'] ?? []),
            'recommendations' => $this->stringList($payload['recommendations'] ?? []),
            'risks' => $this->stringList($payload['risks'] ?? []),
        ];
    }

    private function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, 'is_string'));
    }

    private function fallbackLeadScore(Lead $lead): LeadInsightData
    {
        $score = 45;

        if ($lead->phone) {
            $score += 15;
        }

        if ($lead->company) {
            $score += 15;
        }

        if (str_contains(strtolower($lead->campaign), 'google')) {
            $score += 10;
        }

        $temperature = match (true) {
            $score >= 75 => 'hot',
            $score >= 50 => 'warm',
            default => 'cold',
        };

        return new LeadInsightData(
            score: min(100, $score),
            temperature: $temperature,
            summary: 'Scoring local basado en completitud de datos y campana.',
            recommendedAction: $temperature === 'hot' ? 'Contactar antes de 24 horas.' : 'Nutrir con seguimiento comercial.',
            signals: ['fallback_rules'],
        );
    }
}
