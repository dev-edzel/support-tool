<?php

namespace App\Http\Requests\Auth;

use App\Models\Role;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

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
        if ($this->method() == "POST") {
            return [
                'username' => ['required', 'string', 'unique:users,username'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'min:8', 'confirmed'],
                'role_id' => ['required', Rule::in(
                    Role::ROLE_ADMINISTRATOR,
                    Role::ROLE_SUPPORT
                )],
            ];
        } else {
            return [
                'username' => ['nullable', 'string', 'unique:users,username'],
                'email' => ['nullable', 'string', 'unique:users,email'],
            ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response($validator->errors(), Response::HTTP_BAD_REQUEST)
        );
    }
}
