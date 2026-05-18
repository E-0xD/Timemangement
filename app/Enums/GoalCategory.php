<?php

namespace App\Enums;

enum GoalCategory: string
{
    case StudyHours          = 'study_hours';
    case Gpa                 = 'gpa';
    case AssignmentCompletion = 'assignment_completion';
    case Reading             = 'reading';
    case Revision            = 'revision';

    public function label(): string
    {
        return match ($this) {
            self::StudyHours           => 'Study Hours',
            self::Gpa                  => 'GPA',
            self::AssignmentCompletion => 'Assignment Completion',
            self::Reading              => 'Reading',
            self::Revision             => 'Revision',
        };
    }

    public function unit(): string
    {
        return match ($this) {
            self::StudyHours           => 'hours',
            self::Gpa                  => 'points',
            self::AssignmentCompletion => '%',
            self::Reading              => 'pages',
            self::Revision             => 'topics',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
