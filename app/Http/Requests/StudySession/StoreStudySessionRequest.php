<?php

namespace App\Http\Requests\StudySession;

use App\Enums\StudySessionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreStudySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'type'             => ['required', Rule::enum(StudySessionType::class)],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:480'],
            'course_id'        => ['nullable', Rule::exists('courses', 'id')->where('user_id', Auth::id())],
            'task_id'          => ['nullable', Rule::exists('tasks', 'id')->where('user_id', Auth::id())],
            'notes'            => ['nullable', 'string', 'max:500'],
        ];
    }
}
