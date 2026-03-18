<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|min:4|max:255",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Name is requires.",
            "name.string" => "Name must be string.",
            "name.min" => "Name must be at least 5 characters.",
            "name.max" => "Name must be not exceed 25 characters.",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            ApiResponse::errorResponse(
                "The request could not be completed due to validation errors.",
                $errors,
                422
            )
        );
    }
}
