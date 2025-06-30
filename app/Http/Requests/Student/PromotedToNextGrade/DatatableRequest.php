<?php

namespace App\Http\Requests\Student\PromotedToNextGrade;

use Illuminate\Foundation\Http\FormRequest;

class DatatableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable'],
            'current_class_level' => ['nullable', 'integer', 'exists:class_levels,id'],
            'current_level' => ['required', 'in:yes,no'],
            'next_level' => ['required', 'in:yes,no']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
