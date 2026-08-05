<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\LeadAiAnalyzerContract;
use App\Contracts\LeadRepositoryContract;
use App\Repositories\EloquentLeadRepository;
use App\Services\AI\GeminiClient;
use App\Services\AI\JsonResponseParser;
use App\Services\AI\LeadAiAnalyzer;
use App\Services\AI\OpenAiCompatibleClient;
use Illuminate\Support\ServiceProvider;

class MarketingLeadsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LeadRepositoryContract::class, EloquentLeadRepository::class);
        $this->app->bind(LeadAiAnalyzerContract::class, LeadAiAnalyzer::class);

        $this->app->bind(OpenAiCompatibleClient::class, function ($app): OpenAiCompatibleClient {
            $provider = config('ai.default') === 'openai' ? 'openai' : 'groq';

            return new OpenAiCompatibleClient(
                config("ai.providers.{$provider}"),
                $app->make(JsonResponseParser::class)
            );
        });

        $this->app->bind(GeminiClient::class, fn ($app): GeminiClient => new GeminiClient(
            config('ai.providers.gemini'),
            $app->make(JsonResponseParser::class)
        ));
    }
}
