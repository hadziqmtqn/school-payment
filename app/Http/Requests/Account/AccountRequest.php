<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . auth()->user()->username . ',username'],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:500'],
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
            'password' => 'kata sandi',
            'photo' => 'foto'
        ];
    }
}
