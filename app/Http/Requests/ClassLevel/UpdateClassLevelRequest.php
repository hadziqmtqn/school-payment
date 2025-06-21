<?php

namespace App\Http\Requests\ClassLevel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassLevelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'serial_number' => ['required', 'integer', 'unique:class_levels,serial_number,' . $this->route('classLevel')->slug . ',slug'],
            'name' => ['required'],
            'is_active' => ['required', 'boolean'],
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
            'name' => 'nama',
            'is_active' => 'status aktif'
        ];
    }
}
