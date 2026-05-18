<?php

namespace App\Enums;

enum NotificationChannel: string
{
    case InApp    = 'in_app';
    case Email    = 'email';
    case Telegram = 'telegram';

    public function label(): string
    {
        return match ($this) {
            self::InApp    => 'In-App',
            self::Email    => 'Email',
            self::Telegram => 'Telegram',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
