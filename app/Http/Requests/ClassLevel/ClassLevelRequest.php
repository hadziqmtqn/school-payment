<?php

namespace App\Http\Requests\ClassLevel;

use Illuminate\Foundation\Http\FormRequest;

class ClassLevelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'serial_number' => ['required', 'integer', 'unique:class_levels,serial_number'],
            'name' => ['required']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'serial_number' => 'no. urut',
            'name' => 'nama'
        ];
    }
}
