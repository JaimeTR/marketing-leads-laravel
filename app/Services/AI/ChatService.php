<?php

declare(strict_types=1);

namespace App\Services\AI;

use App\Services\Leads\LeadService;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatService
{
    public function __construct(
        private readonly LeadPromptFactory $prompts,
        private readonly OpenAiCompatibleClient $openAiCompatible,
        private readonly GeminiClient $gemini,
        private readonly LeadService $leads,
    ) {
    }

    public function ask(string $message): string
    {
        $client = config('ai.default') === 'gemini'
            ? $this->gemini
            : $this->openAiCompatible;

        try {
            $context = $this->leads->chatContext();
            $reply = $client->completeText($this->prompts->chatPrompt($message, $context));

            return $reply !== '' ? $reply : 'Lo siento, no pude generar una respuesta.';
        } catch (Throwable $exception) {
            Log::warning('AI chat failed', ['message' => $exception->getMessage()]);

            return 'Hubo un error al contactar el asistente. Reintenta en unos segundos.';
        }
    }
}