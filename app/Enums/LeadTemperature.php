<?php

declare(strict_types=1);

namespace App\Enums;

enum LeadTemperature: string
{
    case Cold = 'cold';
    case Warm = 'warm';
    case Hot = 'hot';

    public function label(): string
    {
        return match ($this) {
            self::Cold => 'Lead frio',
            self::Warm => 'Lead tibio',
            self::Hot => 'Lead caliente',
        };
    }
}
