<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => ['required'],
            'user_id' => ['required', 'integer'],
            'school_year_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'sub_class_level_id' => ['required', 'integer'],
            'is_active' => ['boolean'],
            'is_graduate' => ['nullable', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
