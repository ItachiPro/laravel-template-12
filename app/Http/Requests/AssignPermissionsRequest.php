<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssignPermissionsRequest extends FormRequest
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
            "permissions" => "present|array",
            "permissions.*" => "exists:permissions,id"
        ];
    }

    public function messages(): array
    {
        return [
            "permissions.required" => "At least one permission is required.",
            "permissions.array" => "The permissions field must be an array.",
            "permissions.*.exists" => "One or more selected permissions are invalid."
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
