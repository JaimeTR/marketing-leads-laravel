<?php

declare(strict_types=1);

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class GeminiClient
{
    public function __construct(
        private readonly array $config,
        private readonly JsonResponseParser $parser,
    ) {
    }

    public function complete(array $prompt): array
    {
        if (blank($this->config['api_key'] ?? null)) {
            return [];
        }

        $url = sprintf(
            '%s/models/%s:generateContent?key=%s',
            rtrim($this->config['base_url'], '/'),
            $this->config['model'],
            $this->config['api_key']
        );

        $response = Http::timeout(config('ai.timeout'))
            ->post($url, [
                'contents' => [[
                    'parts' => [[
                        'text' => $prompt['system'] . PHP_EOL . $prompt['user'],
                    ]],
                ]],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ],
            ])
            ->throw()
            ->json();

        return $this->parser->parse(data_get($response, 'candidates.0.content.parts.0.text', ''));
    }

    public function completeText(array $prompt): string
    {
        if (blank($this->config['api_key'] ?? null)) {
            return '';
        }

        $url = sprintf(
            '%s/models/%s:generateContent?key=%s',
            rtrim($this->config['base_url'], '/'),
            $this->config['model'],
            $this->config['api_key']
        );

        $response = Http::timeout(config('ai.timeout'))
            ->post($url, [
                'contents' => [[
                    'parts' => [[
                        'text' => $prompt['system'] . PHP_EOL . $prompt['user'],
                    ]],
                ]],
            ])
            ->throw()
            ->json();

        return trim((string) data_get($response, 'candidates.0.content.parts.0.text', ''));
    }
}
