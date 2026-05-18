<?php

namespace App\Services;

use App\Enums\AchievementType;
use App\Enums\TaskStatus;
use App\Models\Achievement;
use App\Models\Goal;
use App\Models\StudySession;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Log;

class AwardAchievementService
{
    public function checkAndAward(User $user): void
    {
        try {
            $earnedIds = UserAchievement::where('user_id', $user->id)
                ->pluck('achievement_id')
                ->all();

            $candidates = Achievement::whereNotIn('id', $earnedIds)->get();

            foreach ($candidates as $achievement) {
                if ($this->meetsThreshold($user, $achievement)) {
                    UserAchievement::create([
                        'user_id'        => $user->id,
                        'achievement_id' => $achievement->id,
                        'earned_at'      => now(),
                    ]);

                    $user->increment('xp_points', $achievement->xp_value);
                }
            }
        } catch (\Exception $e) {
            Log::error('AwardAchievementService@checkAndAward failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    private function meetsThreshold(User $user, Achievement $achievement): bool
    {
        $user->refresh();

        return match ($achievement->type) {
            AchievementType::StudyStreak => $user->study_streak >= $achievement->threshold,

            AchievementType::TasksComplete => $user->tasks()
                ->where('status', TaskStatus::Completed->value)
                ->count() >= $achievement->threshold,

            AchievementType::StudyHours => (int) floor(
                StudySession::where('user_id', $user->id)->sum('duration_minutes') / 60
            ) >= $achievement->threshold,

            AchievementType::PerfectWeek => $this->hasPerfectWeek($user, $achievement->threshold),

            AchievementType::GoalReached => Goal::where('user_id', $user->id)
                ->where('is_completed', true)
                ->count() >= $achievement->threshold,
        };
    }

    private function hasPerfectWeek(User $user, int $daysRequired): bool
    {
        // Count distinct days with study activity in the past week
        $distinctDays = StudySession::where('user_id', $user->id)
            ->where('started_at', '>=', now()->subWeek())
            ->selectRaw('DATE(started_at) as study_date')
            ->distinct()
            ->count();

        return $distinctDays >= $daysRequired;
    }

    public function recordStudySession(User $user, int $durationMinutes): void
    {
        // Award 1 XP per minute studied
        if ($durationMinutes > 0) {
            $user->increment('xp_points', $durationMinutes);
        }

        // Update study streak
        $this->updateStreak($user);

        // Check achievements
        $this->checkAndAward($user);
    }

    public function recordTaskCompletion(User $user): void
    {
        $user->increment('xp_points', 10);
        $this->checkAndAward($user);
    }

    public function recordGoalCompletion(User $user): void
    {
        $user->increment('xp_points', 50);
        $this->checkAndAward($user);
    }

    private function updateStreak(User $user): void
    {
        $user->refresh();
        $today = today();

        if (! $user->last_study_date) {
            $user->study_streak    = 1;
            $user->last_study_date = $today;
        } elseif ($user->last_study_date->eq($today)) {
            // Already counted today — do nothing
            return;
        } elseif ($user->last_study_date->isYesterday()) {
            $user->study_streak   += 1;
            $user->last_study_date = $today;
        } else {
            // Gap — reset
            $user->study_streak    = 1;
            $user->last_study_date = $today;
        }

        $user->save();
    }
}
