<?php

namespace App\Enums;

enum NotificationType: string
{
    case DeadlineReminder    = 'deadline_reminder';
    case AssignmentReminder  = 'assignment_reminder';
    case ExamReminder        = 'exam_reminder';
    case StudyReminder       = 'study_reminder';
    case ClassReminder       = 'class_reminder';
    case GoalReminder        = 'goal_reminder';
    case DailySummary        = 'daily_summary';
    case WeeklySummary       = 'weekly_summary';
    case ProductivityReminder = 'productivity_reminder';
    case Achievement         = 'achievement';
    case System              = 'system';

    public function label(): string
    {
        return match ($this) {
            self::DeadlineReminder     => 'Deadline Reminder',
            self::AssignmentReminder   => 'Assignment Reminder',
            self::ExamReminder         => 'Exam Reminder',
            self::StudyReminder        => 'Study Reminder',
            self::ClassReminder        => 'Class Reminder',
            self::GoalReminder         => 'Goal Reminder',
            self::DailySummary         => 'Daily Summary',
            self::WeeklySummary        => 'Weekly Summary',
            self::ProductivityReminder => 'Productivity Reminder',
            self::Achievement          => 'Achievement Unlocked',
            self::System               => 'System',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
