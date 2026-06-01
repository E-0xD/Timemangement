<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StudySession\StoreStudySessionRequest;
use App\Models\StudySession;
use App\Services\AwardAchievementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StudySessionController extends Controller
{
    public function focus(): View
    {
        return $this->loadFocusView();
    }

    public function settings(): View
    {
        return view('focus.settings');
    }

    private function loadFocusView(bool $showSettings = false): View
    {
        $todaySessions = StudySession::where('user_id', Auth::id())
            ->whereDate('started_at', today())
            ->whereNotNull('ended_at')
            ->orderByDesc('started_at')
            ->get();

        $todayMinutes = $todaySessions->sum('duration_minutes');

        $courses = Auth::user()->courses()->orderBy('name')->get();

        $tasks = Auth::user()->tasks()
            ->whereIn('status', [TaskStatus::Pending->value, TaskStatus::InProgress->value])
            ->orderByRaw("FIELD(status, 'in_progress', 'pending')")
            ->orderBy('due_date')
            ->limit(15)
            ->get();

        return view('focus.index', compact('todaySessions', 'todayMinutes', 'courses', 'tasks', 'showSettings'));
    }

    public function store(StoreStudySessionRequest $request): RedirectResponse
    {
        $duration = (int) $request->validated('duration_minutes');

        try {
            StudySession::create([
                'user_id'          => Auth::id(),
                'course_id'        => $request->validated('course_id') ?: null,
                'task_id'          => $request->validated('task_id') ?: null,
                'type'             => $request->validated('type'),
                'started_at'       => now()->subMinutes($duration),
                'ended_at'         => now(),
                'duration_minutes' => $duration,
                'notes'            => $request->validated('notes'),
            ]);

            (new AwardAchievementService())->recordStudySession(Auth::user(), $duration);

            return back()->with('session_saved', true);
        } catch (\Throwable $e) {
            Log::error('StudySession store failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroy(StudySession $session): RedirectResponse
    {
        abort_unless($session->user_id === Auth::id(), 403);

        try {
            $session->delete();
        } catch (\Throwable $e) {
            Log::error('StudySession destroy failed', ['id' => $session->id, 'error' => $e->getMessage()]);
            throw $e;
        }

        return back()->with('success', 'Session deleted.');
    }
}
