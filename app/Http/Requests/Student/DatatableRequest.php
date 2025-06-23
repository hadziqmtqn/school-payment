<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class DatatableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['numeric', 'min:3'],
            'school_year_id' => ['required', 'integer', 'exists:school_years,id'],
            'class_level_id' => ['nullable', 'integer', 'exists:class_levels,id'],
            'sub_class_level_id' => ['nullable', 'integer', 'exists:sub_class_levels,id'],
            'is_active' => ['nullable', 'in:active,inactive,deleted'],
            'is_graduate' => ['nullable', 'in:yes,no']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'search' => 'pencarian',
            'school_year_id' => 'tahun ajaran',
            'class_level_id' => 'level kelas',
            'sub_class_level_id' => 'sub level kelas',
            'is_active' => 'status aktif',
            'is_graduate' => 'status lulus'
        ];
    }
}
