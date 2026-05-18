<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        try {
            $userId = Auth::id();
            $today  = now()->toDateString();

            $pendingCount = Task::where('user_id', $userId)
                ->where('status', TaskStatus::Pending->value)
                ->count();

            $inProgressCount = Task::where('user_id', $userId)
                ->where('status', TaskStatus::InProgress->value)
                ->count();

            $dueTodayCount = Task::where('user_id', $userId)
                ->whereNotIn('status', [TaskStatus::Completed->value, TaskStatus::Cancelled->value])
                ->whereDate('due_date', $today)
                ->count();

            $overdueCount = Task::where('user_id', $userId)
                ->whereNotIn('status', [TaskStatus::Completed->value, TaskStatus::Cancelled->value])
                ->whereDate('due_date', '<', $today)
                ->whereNotNull('due_date')
                ->count();

            $upcomingTasks = Task::where('user_id', $userId)
                ->whereNotIn('status', [TaskStatus::Completed->value, TaskStatus::Cancelled->value])
                ->whereNotNull('due_date')
                ->with('course')
                ->orderBy('due_date')
                ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
                ->limit(8)
                ->get();

            $recentlyCompleted = Task::where('user_id', $userId)
                ->where('status', TaskStatus::Completed->value)
                ->with('course')
                ->orderByDesc('completed_at')
                ->limit(4)
                ->get();

            return view('dashboard', compact(
                'pendingCount',
                'inProgressCount',
                'dueTodayCount',
                'overdueCount',
                'upcomingTasks',
                'recentlyCompleted',
            ));
        } catch (\Throwable $e) {
            Log::error('DashboardController error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
