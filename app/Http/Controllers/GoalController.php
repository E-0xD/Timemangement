<?php

namespace App\Http\Controllers;

use App\Enums\GoalCategory;
use App\Enums\GoalPeriod;
use App\Http\Requests\Goal\StoreGoalRequest;
use App\Http\Requests\Goal\UpdateGoalRequest;
use App\Models\Goal;
use App\Services\AwardAchievementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GoalController extends Controller
{
    public function index(Request $request): View
    {
        try {
            $filter = $request->query('filter', 'all');

            $query = Goal::where('user_id', Auth::id())
                ->orderByRaw('is_completed ASC')
                ->orderByRaw('CASE WHEN target_date IS NULL THEN 1 ELSE 0 END')
                ->orderBy('target_date');

            if ($filter === 'active') {
                $query->where('is_completed', false);
            } elseif ($filter === 'completed') {
                $query->where('is_completed', true);
            }

            $goals = $query->get();

            return view('goals.index', compact('goals', 'filter'));
        } catch (\Exception $e) {
            Log::error('GoalController@index failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function create(): View
    {
        $categories = GoalCategory::cases();
        $periods    = GoalPeriod::cases();

        return view('goals.create', compact('categories', 'periods'));
    }

    public function store(StoreGoalRequest $request): RedirectResponse
    {
        try {
            Goal::create([
                ...$request->validated(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('goals.index')->with('success', 'Goal created successfully.');
        } catch (\Exception $e) {
            Log::error('GoalController@store failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function edit(Goal $goal): View
    {
        abort_unless($goal->user_id === Auth::id(), 403);

        $categories = GoalCategory::cases();
        $periods    = GoalPeriod::cases();

        return view('goals.edit', compact('goal', 'categories', 'periods'));
    }

    public function update(UpdateGoalRequest $request, Goal $goal): RedirectResponse
    {
        abort_unless($goal->user_id === Auth::id(), 403);

        try {
            $data = $request->validated();

            // Auto-mark completed if value reaches target
            if (isset($data['current_value']) && $data['current_value'] >= ($data['target_value'] ?? $goal->target_value)) {
                $data['is_completed'] = true;
                $data['completed_at'] = $goal->completed_at ?? now();
            }

            $goal->update($data);

            return redirect()->route('goals.index')->with('success', 'Goal updated.');
        } catch (\Exception $e) {
            Log::error('GoalController@update failed', [
                'user_id' => Auth::id(),
                'goal_id' => $goal->id,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function destroy(Goal $goal): RedirectResponse
    {
        abort_unless($goal->user_id === Auth::id(), 403);

        try {
            $goal->delete();

            return redirect()->route('goals.index')->with('success', 'Goal deleted.');
        } catch (\Exception $e) {
            Log::error('GoalController@destroy failed', [
                'user_id' => Auth::id(),
                'goal_id' => $goal->id,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateProgress(Request $request, Goal $goal): RedirectResponse
    {
        abort_unless($goal->user_id === Auth::id(), 403);

        try {
            $validated = $request->validate([
                'current_value' => ['required', 'numeric', 'min:0'],
            ]);

            $wasCompleted = (bool) $goal->is_completed;
            $goal->current_value = $validated['current_value'];

            if ((float) $goal->current_value >= (float) $goal->target_value) {
                $goal->is_completed = true;
                $goal->completed_at = $goal->completed_at ?? now();
            } else {
                $goal->is_completed = false;
                $goal->completed_at = null;
            }

            $goal->save();

            if ($goal->is_completed && !$wasCompleted) {
                (new AwardAchievementService())->recordGoalCompletion(Auth::user());
            }

            return back()->with('success', 'Progress updated.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('GoalController@updateProgress failed', [
                'user_id' => Auth::id(),
                'goal_id' => $goal->id,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
