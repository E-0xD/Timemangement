<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('department')->latest();

        if ($search = strip_tags((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => UserRole::cases(),
        ]);
    }

    public function show(User $user): View
    {
        $user->load('department', 'courses');

        $totalTasks     = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $studyMinutes   = (int) $user->studySessions()->sum('duration_minutes');
        $totalGoals     = $user->goals()->count();

        return view('admin.users.show', compact(
            'user', 'totalTasks', 'completedTasks', 'studyMinutes', 'totalGoals'
        ));
    }

    public function edit(User $user): View
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'          => ['required', Rule::in(UserRole::values())],
            'school'        => ['nullable', 'string', 'max:255'],
            'bio'           => ['nullable', 'string', 'max:500'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        try {
            $user->update($validated);
        } catch (\Throwable $e) {
            Log::error('Admin UserController@update failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            throw $e;
        }

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->id !== Auth::id(), 403, 'Cannot delete your own account.');

        try {
            $user->delete();
        } catch (\Throwable $e) {
            Log::error('Admin UserController@destroy failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            throw $e;
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function toggleRole(User $user): RedirectResponse
    {
        abort_unless($user->id !== Auth::id(), 403, 'Cannot change your own role.');

        try {
            $user->update([
                'role' => $user->isAdmin() ? UserRole::Student->value : UserRole::Admin->value,
            ]);
        } catch (\Throwable $e) {
            Log::error('Admin UserController@toggleRole failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            throw $e;
        }

        return back()->with('success', 'Role updated.');
    }
}
