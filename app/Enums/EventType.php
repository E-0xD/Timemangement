<?php

namespace App\Enums;

enum EventType: string
{
    case Class_       = 'class';
    case Exam         = 'exam';
    case Assignment   = 'assignment';
    case Meeting      = 'meeting';
    case StudySession = 'study_session';
    case GroupStudy   = 'group_study';
    case Other        = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Class_       => 'Class',
            self::Exam         => 'Exam',
            self::Assignment   => 'Assignment',
            self::Meeting      => 'Meeting',
            self::StudySession => 'Study Session',
            self::GroupStudy   => 'Group Study',
            self::Other        => 'Other',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Class_       => '#3B82F6',
            self::Exam         => '#EF4444',
            self::Assignment   => '#F59E0B',
            self::Meeting      => '#8B5CF6',
            self::StudySession => '#10B981',
            self::GroupStudy   => '#06B6D4',
            self::Other        => '#6B7280',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
