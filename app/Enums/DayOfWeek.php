<?php

namespace App\Enums;

enum DayOfWeek: string
{
    case Monday    = 'monday';
    case Tuesday   = 'tuesday';
    case Wednesday = 'wednesday';
    case Thursday  = 'thursday';
    case Friday    = 'friday';
    case Saturday  = 'saturday';
    case Sunday    = 'sunday';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function short(): string
    {
        return match ($this) {
            self::Monday    => 'Mon',
            self::Tuesday   => 'Tue',
            self::Wednesday => 'Wed',
            self::Thursday  => 'Thu',
            self::Friday    => 'Fri',
            self::Saturday  => 'Sat',
            self::Sunday    => 'Sun',
        };
    }

    public function isWeekend(): bool
    {
        return in_array($this, [self::Saturday, self::Sunday]);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function weekdays(): array
    {
        return [
            self::Monday,
            self::Tuesday,
            self::Wednesday,
            self::Thursday,
            self::Friday,
        ];
    }
}
