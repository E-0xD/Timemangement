<?php

namespace App\Http\Requests\Goal;

use App\Enums\GoalCategory;
use App\Enums\GoalPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'category'      => ['required', Rule::enum(GoalCategory::class)],
            'period'        => ['required', Rule::enum(GoalPeriod::class)],
            'target_value'  => ['required', 'numeric', 'min:0.01', 'max:99999'],
            'current_value' => ['nullable', 'numeric', 'min:0'],
            'target_date'   => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}
