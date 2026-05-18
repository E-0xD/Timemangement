<?php

namespace App\Enums;

enum StudySessionType: string
{
    case Focus    = 'focus';
    case Break    = 'break';
    case Pomodoro = 'pomodoro';

    public function label(): string
    {
        return match ($this) {
            self::Focus    => 'Focus Session',
            self::Break    => 'Break',
            self::Pomodoro => 'Pomodoro',
        };
    }

    public function defaultDurationMinutes(): int
    {
        return match ($this) {
            self::Focus    => 60,
            self::Break    => 10,
            self::Pomodoro => 25,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
