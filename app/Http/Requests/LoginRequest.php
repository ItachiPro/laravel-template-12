<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            "email" => "required|string|email",
            "password" => "required|string"
        ];
    }

    public function messages(): array
    {
        return [
            "email.required" => "Email is required.",
            "email.string" => "Email must be string.",
            "email.email" => "Email is invalid.",
            "password.required" => "Password is required.",
            "password.string" => "Password must be string.",
        ];
    }

    public function failedValidation(Validator $validator)
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
