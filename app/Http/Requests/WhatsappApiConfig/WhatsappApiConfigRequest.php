<?php

namespace App\Http\Requests\WhatsappApiConfig;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappApiConfigRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'endpoint' => ['required', 'url'],
            'api_key' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
