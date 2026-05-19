<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:50'],
            'lecturer'      => ['nullable', 'string', 'max:255'],
            'color'         => ['nullable', 'regex:/^#[0-9a-f]{6}$/i'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'semester_id'   => ['nullable', 'integer', 'exists:semesters,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'color.regex' => 'Invalid color format. Use hex color (e.g. #6366f1).',
        ];
    }
}
