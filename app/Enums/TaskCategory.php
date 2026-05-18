<?php

namespace App\Enums;

enum TaskCategory: string
{
    case Homework   = 'homework';
    case Assignment = 'assignment';
    case Exam       = 'exam';
    case Project    = 'project';
    case Personal   = 'personal';
    case GroupWork  = 'group_work';

    public function label(): string
    {
        return match ($this) {
            self::Homework   => 'Homework',
            self::Assignment => 'Assignment',
            self::Exam       => 'Exam',
            self::Project    => 'Project',
            self::Personal   => 'Personal',
            self::GroupWork  => 'Group Work',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
