<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\StoreCourseRequest;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\StoreSemesterRequest;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        try {
            $user = Auth::user();

            $departments = $user->ownedDepartments()->with('courses.semester')->get();
            $semesters   = $user->ownedSemesters()->with('courses')->orderBy('start_date', 'desc')->get();

            return view('departments.index', compact('departments', 'semesters'));
        } catch (\Throwable $e) {
            Log::error('DepartmentController@index error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function storeDepartment(StoreDepartmentRequest $request): RedirectResponse
    {
        try {
            Auth::user()->ownedDepartments()->create([
                ...$request->validated(),
                'is_custom' => true,
            ]);

            return redirect()->route('departments.index')->with('success', 'Department created successfully.');
        } catch (\Throwable $e) {
            Log::error('DepartmentController@storeDepartment error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroyDepartment(Department $department): RedirectResponse
    {
        try {
            if ($department->user_id !== Auth::id()) {
                abort(403);
            }

            $department->delete();

            return redirect()->route('departments.index')->with('success', 'Department deleted.');
        } catch (\Throwable $e) {
            Log::error('DepartmentController@destroyDepartment error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function storeCourse(StoreCourseRequest $request): RedirectResponse
    {
        try {
            $user       = Auth::user();
            $department = $user->ownedDepartments()->findOrFail($request->validated()['department_id']);

            $user->courses()->create([
                ...$request->validated(),
                'department_id' => $department->id,
            ]);

            return redirect()->route('departments.index')->with('success', 'Course created successfully.');
        } catch (\Throwable $e) {
            Log::error('DepartmentController@storeCourse error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroyCourse(Course $course): RedirectResponse
    {
        try {
            if ($course->user_id !== Auth::id()) {
                abort(403);
            }

            $course->delete();

            return redirect()->route('departments.index')->with('success', 'Course deleted.');
        } catch (\Throwable $e) {
            Log::error('DepartmentController@destroyCourse error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function storeSemester(StoreSemesterRequest $request): RedirectResponse
    {
        try {
            Auth::user()->ownedSemesters()->create([
                ...$request->validated(),
                'is_custom'  => true,
                'is_current' => false,
            ]);

            return redirect()->route('departments.index')->with('success', 'Semester created successfully.');
        } catch (\Throwable $e) {
            Log::error('DepartmentController@storeSemester error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroySemester(Semester $semester): RedirectResponse
    {
        try {
            if ($semester->user_id !== Auth::id()) {
                abort(403);
            }

            $semester->delete();

            return redirect()->route('departments.index')->with('success', 'Semester deleted.');
        } catch (\Throwable $e) {
            Log::error('DepartmentController@destroySemester error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
