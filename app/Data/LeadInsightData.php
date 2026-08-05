<?php

declare(strict_types=1);

namespace App\Data;

final readonly class LeadInsightData
{
    public function __construct(
        public int $score,
        public string $temperature,
        public string $summary,
        public string $recommendedAction,
        public array $signals = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            score: max(0, min(100, (int) ($data['score'] ?? 0))),
            temperature: (string) ($data['temperature'] ?? 'warm'),
            summary: (string) ($data['summary'] ?? 'Sin resumen disponible.'),
            recommendedAction: (string) ($data['recommended_action'] ?? 'Contactar al lead.'),
            signals: is_array($data['signals'] ?? null) ? $data['signals'] : [],
        );
    }

    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'temperature' => $this->temperature,
            'summary' => $this->summary,
            'recommended_action' => $this->recommendedAction,
            'signals' => $this->signals,
        ];
    }
}
