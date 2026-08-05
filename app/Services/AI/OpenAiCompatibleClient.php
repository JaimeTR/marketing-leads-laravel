<?php

declare(strict_types=1);

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class OpenAiCompatibleClient
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

        $response = Http::timeout(config('ai.timeout'))
            ->withToken($this->config['api_key'])
            ->post(rtrim($this->config['base_url'], '/') . '/chat/completions', [
                'model' => $this->config['model'],
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $prompt['system']],
                    ['role' => 'user', 'content' => $prompt['user']],
                ],
            ])
            ->throw()
            ->json();

        return $this->parser->parse(data_get($response, 'choices.0.message.content', ''));
    }

    public function completeText(array $prompt): string
    {
        if (blank($this->config['api_key'] ?? null)) {
            return '';
        }

        $response = Http::timeout(config('ai.timeout'))
            ->withToken($this->config['api_key'])
            ->post(rtrim($this->config['base_url'], '/') . '/chat/completions', [
                'model' => $this->config['model'],
                'messages' => [
                    ['role' => 'system', 'content' => $prompt['system']],
                    ['role' => 'user', 'content' => $prompt['user']],
                ],
            ])
            ->throw()
            ->json();

        return trim((string) data_get($response, 'choices.0.message.content', ''));
    }
}
