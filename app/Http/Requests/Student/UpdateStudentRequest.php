<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email,' . $this->route('user')->username . ',username'],
            'password' => ['nullable', 'string', 'min:8'],
            'password_confirmation' => ['nullable', 'same:password'],
            'reg_number' => ['required', 'unique:students,reg_number,' . $this->route('user')->id . ',user_id'],
            'is_graduate' => ['required', 'boolean'],
            'class_level_id' => ['required_if:is_graduate,0', 'nullable', 'integer', 'exists:class_levels,id'],
            'sub_class_level_id' => ['required_if:is_graduate,0', 'nullable', 'integer', 'exists:sub_class_levels,id'],
            'whatsapp_number' => ['required', 'min_digits:10', 'max_digits:13'],
            'gender' => ['required', 'in:L,P'],
            'is_active' => ['required', 'boolean']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'kata sandi',
            'password_confirmation' => 'konfirmasi kata sandi',
            'reg_number' => 'no. registrasi',
            'is_graduate' => 'telah lulus',
            'class_level_id' => 'level kelas',
            'sub_class_level_id' => 'sub level kelas',
            'whatsapp_number' => 'no. Whatsapp',
            'gender' => 'jenis kelamin',
            'is_active' => 'status aktif'
        ];
    }
}
