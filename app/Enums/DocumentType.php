<?php
namespace App\Enums;

enum DocumentType: string
{
    case ProfilePhoto = 'profile_photo';
    case Ci = 'ci';
    case Permis = 'permis';
    case Selfie = 'selfie';
    case Cui = 'cui';
    case Logo = 'logo';
    case RCA = 'rca';
    case CASCO = 'casco';
    case TALON = 'talon';

    public static function values(): array
    {
        return array_map(
            fn(self $case) => $case->value,
            self::cases()
        );
    }

    public static function carDocumentTypes(): array
    {
        return [
            self::RCA->value,
            self::CASCO->value,
            self::TALON->value,
        ];
    }

    public static function personalDocumentTypes(): array
    {
        return [
            self::ProfilePhoto->value,
            self::Ci->value,
            self::Permis->value,
            self::Selfie->value,
            self::Cui->value,
            self::Logo->value,
        ];
    }
}