<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
            'class_level_id' => ['required', 'integer', 'exists:class_levels,id'],
            'sub_class_level_id' => ['required', 'integer', 'exists:sub_class_levels,id'],
            'whatsapp_number' => ['required', 'min_digits:10', 'max_digits:13'],
            'gender' => ['required', 'in:L,P'],
            'send_detail_account' => ['required', 'boolean']
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
            'class_level_id' => 'level kelas',
            'sub_class_level_id' => 'sub level kelas',
            'whatsapp_number' => 'no. Whatsapp',
            'gender' => 'jenis kelamin',
            'send_detail_account' => 'kirim detail akun'
        ];
    }
}
