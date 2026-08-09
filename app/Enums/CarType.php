<?php

namespace App\Enums;

enum CarType: string

{
    case Sedan = 'sedan';
    case SUV = 'suv';
    case Hatchback = 'hatchback';
    case Pickup = 'pickup';
    case Van = 'van';
    case Convertible = 'convertible';
    case Coupe = 'coupe';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
