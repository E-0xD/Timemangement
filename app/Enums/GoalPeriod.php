<?php

namespace App\Enums;

enum GoalPeriod: string
{
    case Daily    = 'daily';
    case Weekly   = 'weekly';
    case Monthly  = 'monthly';
    case Semester = 'semester';

    public function label(): string
    {
        return match ($this) {
            self::Daily    => 'Daily',
            self::Weekly   => 'Weekly',
            self::Monthly  => 'Monthly',
            self::Semester => 'Semester',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
