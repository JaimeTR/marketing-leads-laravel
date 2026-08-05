<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AI\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function __construct(private readonly ChatService $chat)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Chat/Index');
    }

    public function send(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message'));

        if ($message === '') {
            return response()->json(['reply' => 'Escribe un mensaje para el asistente.']);
        }

        return response()->json(['reply' => $this->chat->ask($message)]);
    }
}
