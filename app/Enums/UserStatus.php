<?php

namespace App\Enums;

enum UserStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';

    public static function values(): array
    {
        return array_map(fn(self $status) => $status->value, self::cases());
    }

    public static function options(): array
    {
        return array_map(fn(self $status) => [
            'label' => ucfirst(strtolower($status->name)),
            'value' => $status->value,
        ], self::cases());
    }
}