<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\StudySession;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        // ── Last 14 days study data ──────────────────────────────────────────
        $last14 = collect(range(13, 0))->map(fn ($i) => [
            'label'   => now()->subDays($i)->format('D j'),
            'minutes' => (int) StudySession::where('user_id', $userId)
                ->whereDate('started_at', now()->subDays($i)->toDateString())
                ->whereNotNull('ended_at')
                ->sum('duration_minutes'),
        ]);
        $maxMinutes = max($last14->pluck('minutes')->max(), 1);

        // ── Totals ───────────────────────────────────────────────────────────
        $totalMinutes  = (int) StudySession::where('user_id', $userId)->whereNotNull('ended_at')->sum('duration_minutes');
        $totalSessions = StudySession::where('user_id', $userId)->whereNotNull('ended_at')->count();

        $weekMinutes = (int) StudySession::where('user_id', $userId)
            ->whereNotNull('ended_at')
            ->where('started_at', '>=', now()->startOfWeek())
            ->sum('duration_minutes');

        $weekSessions = StudySession::where('user_id', $userId)
            ->whereNotNull('ended_at')
            ->where('started_at', '>=', now()->startOfWeek())
            ->count();

        // ── Task stats ───────────────────────────────────────────────────────
        $taskRows = Task::where('user_id', $userId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $taskTotal = $taskRows->sum();

        $completedThisMonth = Task::where('user_id', $userId)
            ->where('status', TaskStatus::Completed->value)
            ->whereDate('completed_at', '>=', now()->startOfMonth())
            ->count();

        $overdue = Task::where('user_id', $userId)
            ->whereNotIn('status', [TaskStatus::Completed->value, TaskStatus::Cancelled->value])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', today())
            ->count();

        // ── Top courses by study time (last 30 days) ─────────────────────────
        $topCourses = StudySession::where('user_id', $userId)
            ->where('started_at', '>=', now()->subDays(30))
            ->whereNotNull('ended_at')
            ->whereNotNull('course_id')
            ->select('course_id', DB::raw('SUM(duration_minutes) as total_minutes'))
            ->groupBy('course_id')
            ->with('course')
            ->orderByDesc('total_minutes')
            ->limit(6)
            ->get();
        $maxCourseMins = max($topCourses->pluck('total_minutes')->max(), 1);

        // ── Streak ───────────────────────────────────────────────────────────
        $streak = Auth::user()->study_streak;

        return view('analytics.index', compact(
            'last14', 'maxMinutes',
            'totalMinutes', 'totalSessions', 'weekMinutes', 'weekSessions',
            'taskRows', 'taskTotal', 'completedThisMonth', 'overdue',
            'topCourses', 'maxCourseMins',
            'streak'
        ));
    }
}
