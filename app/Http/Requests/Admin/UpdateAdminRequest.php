<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email,' . $this->route('user')->username . ',username'],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
            'whatsapp_number' => ['required', 'min_digits:10', 'max_digits:13'],
            'mark_as_contact' => ['required', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:500'],
            'is_active' => ['boolean']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'password.regex' => ':attribute minimal terdiri dari angka, 1 huruf besar, huruf kecil, dan karakter khusus',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'kata sandi',
            'whatsapp_number' => 'no. whatsapp',
            'mark_as_contact' => 'tandai kontak utama',
            'photo' => 'foto',
            'is_active' => 'status aktif'
        ];
    }
}
