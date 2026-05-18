<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GamificationController extends Controller
{
    public function index(): View
    {
        try {
            $user = Auth::user();

            $earnedIds = UserAchievement::where('user_id', $user->id)
                ->pluck('achievement_id')
                ->all();

            $earned = Achievement::whereIn('id', $earnedIds)
                ->with(['userAchievements' => fn ($q) => $q->where('user_id', $user->id)])
                ->orderBy('type')
                ->get()
                ->map(function (Achievement $a) {
                    $a->earned_at = $a->userAchievements->first()?->earned_at;
                    return $a;
                });

            $locked = Achievement::whereNotIn('id', $earnedIds)
                ->orderBy('threshold')
                ->get();

            $leaderboard = User::where('xp_points', '>', 0)
                ->orderByDesc('xp_points')
                ->limit(10)
                ->get(['id', 'name', 'xp_points', 'study_streak']);

            // XP progress to next level milestone (every 500 XP = 1 level)
            $level      = max(1, (int) floor($user->xp_points / 500) + 1);
            $xpInLevel  = $user->xp_points % 500;
            $xpToNext   = 500 - $xpInLevel;

            return view('gamification.index', compact(
                'user', 'earned', 'locked', 'leaderboard', 'level', 'xpInLevel', 'xpToNext'
            ));
        } catch (\Exception $e) {
            Log::error('GamificationController@index failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
