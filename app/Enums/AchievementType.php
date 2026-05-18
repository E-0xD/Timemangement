<?php

namespace App\Enums;

enum AchievementType: string
{
    case StudyStreak   = 'study_streak';
    case TasksComplete = 'tasks_complete';
    case StudyHours    = 'study_hours';
    case PerfectWeek   = 'perfect_week';
    case GoalReached   = 'goal_reached';

    public function label(): string
    {
        return match ($this) {
            self::StudyStreak   => 'Study Streak',
            self::TasksComplete => 'Tasks Completed',
            self::StudyHours    => 'Study Hours',
            self::PerfectWeek   => 'Perfect Week',
            self::GoalReached   => 'Goal Reached',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
