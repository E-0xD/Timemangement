<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title'               => ['required', 'string', 'max:255'],
            'description'         => ['nullable', 'string', 'max:10000'],
            'course_id'           => ['nullable', 'integer', Rule::exists('courses', 'id')->where('user_id', Auth::id())],
            'category'            => ['required', new Enum(TaskCategory::class)],
            'priority'            => ['required', new Enum(TaskPriority::class)],
            'status'              => ['nullable', new Enum(TaskStatus::class)],
            'due_date'            => ['nullable', 'date'],
            'due_time'            => ['nullable', 'date_format:H:i'],
            'is_recurring'        => ['nullable', 'boolean'],
            'subtasks'            => ['nullable', 'array', 'max:30'],
            'subtasks.*.title'    => ['required', 'string', 'max:255'],
        ];
    }
}
