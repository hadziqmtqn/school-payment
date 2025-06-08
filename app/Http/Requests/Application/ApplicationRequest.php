<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'school_name' => ['required'],
            'notification_method' => ['required', 'in:whatsapp,email'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:500']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama aplikasi',
            'school_name' => 'nama sekolah',
            'notification_method' => 'metode notifikasi'
        ];
    }
}
