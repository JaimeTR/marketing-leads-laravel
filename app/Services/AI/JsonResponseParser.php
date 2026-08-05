<?php

declare(strict_types=1);

namespace App\Services\AI;

class JsonResponseParser
{
    public function parse(string $content): array
    {
        $decoded = json_decode($content, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $content, $matches) === 1) {
            $decoded = json_decode($matches[0], true);
        }

        return is_array($decoded) ? $decoded : [];
    }
}
