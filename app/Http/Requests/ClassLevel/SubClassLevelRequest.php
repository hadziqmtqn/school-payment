<?php

namespace App\Http\Requests\ClassLevel;

use Illuminate\Foundation\Http\FormRequest;

class SubClassLevelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
