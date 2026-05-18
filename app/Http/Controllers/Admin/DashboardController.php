<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyGroup;
use App\Models\StudySession;
use App\Models\Task;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers    = User::count();
        $newThisWeek   = User::where('created_at', '>=', now()->startOfWeek())->count();
        $todaySessions = StudySession::whereDate('started_at', today())->count();
        $totalGroups   = StudyGroup::count();
        $totalTasks    = Task::count();
        $recentUsers   = User::with('department')->latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'newThisWeek', 'todaySessions', 'totalGroups', 'totalTasks', 'recentUsers'
        ));
    }
}
