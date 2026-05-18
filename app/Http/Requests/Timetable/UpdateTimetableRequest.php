<?php

namespace App\Http\Requests\Timetable;

use App\Enums\DayOfWeek;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateTimetableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:100'],
            'course_id'    => ['nullable', Rule::exists('courses', 'id')->where('user_id', Auth::id())],
            'day_of_week'  => ['required', Rule::enum(DayOfWeek::class)],
            'start_time'   => ['required', 'date_format:H:i'],
            'end_time'     => ['required', 'date_format:H:i', 'after:start_time'],
            'location'     => ['nullable', 'string', 'max:100'],
            'lecturer'     => ['nullable', 'string', 'max:100'],
            'color'        => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_recurring' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => 'The end time must be after the start time.',
        ];
    }
}
