<?php

namespace Database\Seeders;

use App\Enums\AchievementType;
use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // Study Streaks
            [
                'name'        => '3-Day Streak',
                'description' => 'Study for 3 consecutive days.',
                'icon'        => '🔥',
                'badge_color' => '#F97316',
                'xp_value'    => 25,
                'type'        => AchievementType::StudyStreak->value,
                'threshold'   => 3,
            ],
            [
                'name'        => '7-Day Study Streak',
                'description' => 'Study for 7 consecutive days.',
                'icon'        => '🔥',
                'badge_color' => '#F97316',
                'xp_value'    => 75,
                'type'        => AchievementType::StudyStreak->value,
                'threshold'   => 7,
            ],
            [
                'name'        => '30-Day Study Streak',
                'description' => 'Study for 30 consecutive days.',
                'icon'        => '🌟',
                'badge_color' => '#F59E0B',
                'xp_value'    => 300,
                'type'        => AchievementType::StudyStreak->value,
                'threshold'   => 30,
            ],
            // Tasks Completed
            [
                'name'        => 'First Task',
                'description' => 'Complete your first task.',
                'icon'        => '✅',
                'badge_color' => '#10B981',
                'xp_value'    => 10,
                'type'        => AchievementType::TasksComplete->value,
                'threshold'   => 1,
            ],
            [
                'name'        => '10 Tasks Done',
                'description' => 'Complete 10 tasks.',
                'icon'        => '✅',
                'badge_color' => '#10B981',
                'xp_value'    => 50,
                'type'        => AchievementType::TasksComplete->value,
                'threshold'   => 10,
            ],
            [
                'name'        => '30 Completed Tasks',
                'description' => 'Complete 30 tasks.',
                'icon'        => '🏆',
                'badge_color' => '#10B981',
                'xp_value'    => 150,
                'type'        => AchievementType::TasksComplete->value,
                'threshold'   => 30,
            ],
            [
                'name'        => 'Assignment Master',
                'description' => 'Complete 100 tasks.',
                'icon'        => '🎓',
                'badge_color' => '#8B5CF6',
                'xp_value'    => 500,
                'type'        => AchievementType::TasksComplete->value,
                'threshold'   => 100,
            ],
            // Study Hours
            [
                'name'        => 'First Hour',
                'description' => 'Study for a total of 1 hour.',
                'icon'        => '⏱️',
                'badge_color' => '#3B82F6',
                'xp_value'    => 10,
                'type'        => AchievementType::StudyHours->value,
                'threshold'   => 1,
            ],
            [
                'name'        => '10 Study Hours',
                'description' => 'Accumulate 10 hours of study.',
                'icon'        => '📚',
                'badge_color' => '#3B82F6',
                'xp_value'    => 75,
                'type'        => AchievementType::StudyHours->value,
                'threshold'   => 10,
            ],
            [
                'name'        => '50 Study Hours',
                'description' => 'Accumulate 50 hours of study.',
                'icon'        => '🧠',
                'badge_color' => '#6366F1',
                'xp_value'    => 300,
                'type'        => AchievementType::StudyHours->value,
                'threshold'   => 50,
            ],
            [
                'name'        => '100 Study Hours',
                'description' => 'Accumulate 100 hours of study.',
                'icon'        => '💎',
                'badge_color' => '#7C3AED',
                'xp_value'    => 1000,
                'type'        => AchievementType::StudyHours->value,
                'threshold'   => 100,
            ],
            // Perfect Week
            [
                'name'        => 'Perfect Week',
                'description' => 'Complete all tasks for an entire week.',
                'icon'        => '⭐',
                'badge_color' => '#F59E0B',
                'xp_value'    => 200,
                'type'        => AchievementType::PerfectWeek->value,
                'threshold'   => 1,
            ],
            // Goal Reached
            [
                'name'        => 'Goal Crusher',
                'description' => 'Reach your first goal.',
                'icon'        => '🎯',
                'badge_color' => '#EF4444',
                'xp_value'    => 100,
                'type'        => AchievementType::GoalReached->value,
                'threshold'   => 1,
            ],
            [
                'name'        => 'Goal Machine',
                'description' => 'Reach 5 goals.',
                'icon'        => '🚀',
                'badge_color' => '#EC4899',
                'xp_value'    => 400,
                'type'        => AchievementType::GoalReached->value,
                'threshold'   => 5,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::firstOrCreate(
                ['name' => $achievement['name']],
                $achievement
            );
        }
    }
}
