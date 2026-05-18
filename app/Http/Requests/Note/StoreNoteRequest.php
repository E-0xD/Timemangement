<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:200'],
            'content'   => ['nullable', 'string'],
            'course_id' => ['nullable', Rule::exists('courses', 'id')->where('user_id', Auth::id())],
        ];
    }
}
