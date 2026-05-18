<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'file'      => [
                'required',
                'file',
                'max:25600', // 25 MB
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,png,jpg,jpeg,gif,webp,zip,csv',
            ],
            'course_id' => ['nullable', Rule::exists('courses', 'id')->where('user_id', Auth::id())],
            'task_id'   => ['nullable', Rule::exists('tasks', 'id')->where('user_id', Auth::id())],
        ];
    }

    public function messages(): array
    {
        return [
            'file.max'   => 'The file must not exceed 25 MB.',
            'file.mimes' => 'Unsupported file type. Allowed: PDF, Word, PowerPoint, Excel, text, images, ZIP, CSV.',
        ];
    }
}
