<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            "name" => "required|string|min:4|max:25",
            "email"=> "required|string|email|unique:users",
            "password" => "required|string|min:8|confirmed"
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Name is required.",
            "name.string" => "Name must be string.",
            "name.min" => "Name must be at least 5 characters.",
            "name.max" => "Name must be not exceed 25 characters.",
            "email.required" => "Email is required.",
            "email.string" => "Email must be string.",
            "email.email" => "Email is not valid.",
            "email.unique" => "Email is already registered.",
            "password.required" => "Password is required.",
            "password.string" => "Password must be string.",
            "password.min" => "Password must be at least 6 characters.",
            "password.confirmed" => "Password confirmation does not match."
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
