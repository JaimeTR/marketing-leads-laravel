<?php

declare(strict_types=1);

use App\Services\AI\JsonResponseParser;

it('parses plain json responses', function (): void {
    $parser = new JsonResponseParser();

    expect($parser->parse('{"score":82,"temperature":"hot"}'))
        ->toMatchArray(['score' => 82, 'temperature' => 'hot']);
});

it('extracts json from provider text', function (): void {
    $parser = new JsonResponseParser();

    expect($parser->parse('Respuesta: {"summary":"ok"}'))
        ->toMatchArray(['summary' => 'ok']);
});
