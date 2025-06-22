<?php

namespace App\Http\Requests\MessageTemplate;

use Illuminate\Foundation\Http\FormRequest;

class MessageTemplateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category' => ['required'],
            'recipient' => ['required'],
            'title' => ['required'],
            'message' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'category' => 'kategori',
            'recipient' => 'penerima',
            'title' => 'judul',
            'message' => 'pesan'
        ];
    }
}
