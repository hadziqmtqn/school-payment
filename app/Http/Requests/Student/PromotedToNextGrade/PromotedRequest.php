<?php

namespace App\Http\Requests\Student\PromotedToNextGrade;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class PromotedRequest extends FormRequest
{
    use ApiResponse;

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'array'],
            'user_id.*' => ['required', 'integer', 'exists:users,id'],
            'promoted' => ['required', 'array'],
            'promoted.*' => ['required', 'in:yes,no'],
            'next_sub_class_level' => ['required', 'array'],
            'next_sub_class_level.*' => ['required', 'integer', 'exists:sub_class_levels,id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse($validator->errors()->first(), null, null, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
