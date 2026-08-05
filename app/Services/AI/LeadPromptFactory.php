<?php

declare(strict_types=1);

namespace App\Services\AI;

use App\Models\Lead;
use Illuminate\Support\Collection;

class LeadPromptFactory
{
    public function leadScorePrompt(Lead $lead): array
    {
        return [
            'system' => 'Eres un analista senior de marketing B2B. Responde solo JSON valido.',
            'user' => sprintf(
                'Evalua este lead y devuelve JSON con score 0-100, temperature cold|warm|hot, summary, recommended_action y signals array. Lead: %s',
                json_encode([
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'company' => $lead->company,
                    'phone' => $lead->phone,
                    'campaign' => $lead->campaign,
                    'source' => $lead->source?->source_name,
                ], JSON_UNESCAPED_SLASHES)
            ),
        ];
    }

    public function dashboardPrompt(Collection $leads, array $metrics): array
    {
        return [
            'system' => 'Eres un growth analyst. Responde solo JSON valido, sin markdown. summary debe ser un string corto (nunca un objeto), y trends, recommendations y risks deben ser arrays de strings.',
            'user' => sprintf(
                'Genera un resumen ejecutivo de leads. Respeta exactamente este esquema: {"summary": "string breve", "trends": ["string"], "recommendations": ["string"], "risks": ["string"]}. Datos: %s',
                json_encode([
                    'metrics' => $metrics,
                    'recent_leads' => $leads->map(fn (Lead $lead) => [
                        'campaign' => $lead->campaign,
                        'source' => $lead->source?->source_name,
                        'score' => $lead->ai_score,
                        'temperature' => $lead->ai_temperature?->value,
                        'created_at' => $lead->created_at?->toDateTimeString(),
                    ])->values(),
                ], JSON_UNESCAPED_SLASHES)
            ),
        ];
    }

    public function predictPrompt(Collection $leads, array $metrics): array
    {
        return [
            'system' => 'Eres un data scientist de marketing. Responde solo JSON valido: {"predictions": ["string", ...]}.',
            'user' => sprintf(
                'Basandote en los datos, genera de 2 a 4 predicciones accionables (por ejemplo sobre volumen esperado, campanas a priorizar o leads calientes). Datos: %s',
                json_encode([
                    'metrics' => $metrics,
                    'recent_leads' => $leads->map(fn (Lead $lead) => [
                        'campaign' => $lead->campaign,
                        'source' => $lead->source?->source_name,
                        'score' => $lead->ai_score,
                        'temperature' => $lead->ai_temperature?->value,
                    ])->values(),
                ], JSON_UNESCAPED_SLASHES)
            ),
        ];
    }

    public function chatPrompt(string $message, array $context): array
    {
        return [
            'system' => 'Eres un asistente de ventas y marketing que gestiona los datos de leads de una empresa. Hablas en espanol, respondes de forma breve y directa, y usas exclusivamente el contexto de datos que se te entrega. Si te piden una accion que no puedes hacer, sugieres que revise el modulo de leads.',
            'user' => sprintf(
                "Mensaje del usuario: %s\n\nContexto de datos:\n%s",
                $message,
                json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ),
        ];
    }
}