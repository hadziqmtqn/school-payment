<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => ['required'],
            'serial_number' => ['required', 'integer'],
            'name' => ['required'],
            'type' => ['required'],
            'main_menu' => ['nullable'],
            'visibility' => ['nullable'],
            'url' => ['required'],
            'icon' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
