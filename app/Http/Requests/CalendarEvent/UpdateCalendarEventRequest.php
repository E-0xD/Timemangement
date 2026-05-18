<?php

namespace App\Http\Requests\CalendarEvent;

use App\Enums\EventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCalendarEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'type'           => ['required', Rule::enum(EventType::class)],
            'start_datetime' => ['required', 'date'],
            'end_datetime'   => ['required', 'date', 'after_or_equal:start_datetime'],
            'is_all_day'     => ['nullable', 'boolean'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'location'       => ['nullable', 'string', 'max:255'],
            'color'          => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'course_id'      => ['nullable', Rule::exists('courses', 'id')->where('user_id', Auth::id())],
            'is_recurring'   => ['nullable', 'boolean'],
        ];
    }
}
