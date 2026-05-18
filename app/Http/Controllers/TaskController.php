<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        try {
            $userId = Auth::id();

            $query = Task::where('user_id', $userId)
                ->with('course')
                ->withCount('subtasks');

            $status   = request()->query('status');
            $priority = request()->query('priority');
            $category = request()->query('category');
            $courseId = request()->query('course_id');
            $search   = request()->query('search');

            if ($status && in_array($status, \App\Enums\TaskStatus::values())) {
                $query->where('status', $status);
            }

            if ($priority && in_array($priority, \App\Enums\TaskPriority::values())) {
                $query->where('priority', $priority);
            }

            if ($category && in_array($category, \App\Enums\TaskCategory::values())) {
                $query->where('category', $category);
            }

            if ($courseId && is_numeric($courseId)) {
                $query->where('course_id', (int) $courseId);
            }

            if ($search) {
                $query->where('title', 'like', '%' . strip_tags($search) . '%');
            }

            $tasks   = $query->orderBy('due_date')->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")->paginate(20)->withQueryString();
            $courses = Auth::user()->courses()->orderBy('name')->get();

            return view('tasks.index', compact('tasks', 'courses'));
        } catch (\Throwable $e) {
            Log::error('TaskController@index error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function create(): View
    {
        try {
            $courses = Auth::user()->courses()->orderBy('name')->get();

            return view('tasks.create', compact('courses'));
        } catch (\Throwable $e) {
            Log::error('TaskController@create error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        try {
            $data     = $request->validated();
            $subtasks = $data['subtasks'] ?? [];
            unset($data['subtasks']);

            $data['user_id']      = Auth::id();
            $data['status']       = $data['status'] ?? TaskStatus::Pending->value;
            $data['is_recurring'] = $request->boolean('is_recurring');

            $task = Task::create($data);

            foreach ($subtasks as $i => $subtask) {
                $task->subtasks()->create([
                    'title'      => $subtask['title'],
                    'sort_order' => $i,
                ]);
            }

            return redirect()->route('tasks.show', $task)
                ->with('success', 'Task created successfully.');
        } catch (\Throwable $e) {
            Log::error('TaskController@store error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function show(Task $task): View
    {
        try {
            abort_unless($task->user_id === Auth::id(), 403);

            $task->load('subtasks', 'course');

            return view('tasks.show', compact('task'));
        } catch (\Throwable $e) {
            Log::error('TaskController@show error', ['user_id' => Auth::id(), 'task_id' => $task->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function edit(Task $task): View
    {
        try {
            abort_unless($task->user_id === Auth::id(), 403);

            $task->load('subtasks');
            $courses      = Auth::user()->courses()->orderBy('name')->get();
            $subtasksData = $task->subtasks->map(fn ($s) => [
                'title'        => $s->title,
                'is_completed' => $s->is_completed,
            ])->values()->toArray();

            return view('tasks.edit', compact('task', 'courses', 'subtasksData'));
        } catch (\Throwable $e) {
            Log::error('TaskController@edit error', ['user_id' => Auth::id(), 'task_id' => $task->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        try {
            abort_unless($task->user_id === Auth::id(), 403);

            $data     = $request->validated();
            $subtasks = $data['subtasks'] ?? null;
            unset($data['subtasks']);

            $data['is_recurring'] = $request->boolean('is_recurring');

            // Track completion timestamp
            $newStatus = $data['status'] ?? null;
            if ($newStatus === TaskStatus::Completed->value && $task->status !== TaskStatus::Completed) {
                $data['completed_at'] = now();
            } elseif ($newStatus && $newStatus !== TaskStatus::Completed->value) {
                $data['completed_at'] = null;
            }

            $task->update($data);

            // Sync subtasks when explicitly sent
            if ($subtasks !== null) {
                $task->subtasks()->delete();
                foreach ($subtasks as $i => $sub) {
                    $task->subtasks()->create([
                        'title'        => $sub['title'],
                        'is_completed' => (bool) ($sub['is_completed'] ?? false),
                        'sort_order'   => $i,
                    ]);
                }
            }

            return redirect()->route('tasks.show', $task)
                ->with('success', 'Task updated successfully.');
        } catch (\Throwable $e) {
            Log::error('TaskController@update error', ['user_id' => Auth::id(), 'task_id' => $task->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroy(Task $task): RedirectResponse
    {
        try {
            abort_unless($task->user_id === Auth::id(), 403);

            $task->delete();

            return redirect()->route('tasks.index')
                ->with('success', 'Task deleted.');
        } catch (\Throwable $e) {
            Log::error('TaskController@destroy error', ['user_id' => Auth::id(), 'task_id' => $task->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function toggle(Task $task): RedirectResponse
    {
        try {
            abort_unless($task->user_id === Auth::id(), 403);

            if ($task->status === TaskStatus::Completed) {
                $task->update(['status' => TaskStatus::Pending->value, 'completed_at' => null]);
            } else {
                $task->update(['status' => TaskStatus::Completed->value, 'completed_at' => now()]);
            }

            return back();
        } catch (\Throwable $e) {
            Log::error('TaskController@toggle error', ['user_id' => Auth::id(), 'task_id' => $task->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function toggleSubtask(Task $task, Subtask $subtask): RedirectResponse
    {
        try {
            abort_unless($task->user_id === Auth::id(), 403);
            abort_unless($subtask->task_id === $task->id, 403);

            $subtask->toggleComplete();

            return back();
        } catch (\Throwable $e) {
            Log::error('TaskController@toggleSubtask error', ['user_id' => Auth::id(), 'task_id' => $task->id, 'subtask_id' => $subtask->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
