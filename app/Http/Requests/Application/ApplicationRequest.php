<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => ['required'],
            'name' => ['required'],
            'notification_method' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
