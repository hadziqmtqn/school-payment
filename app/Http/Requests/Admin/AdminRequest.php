<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'whatsapp_number' => ['required'],
            'mark_as_contact' => ['boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
