<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TicketRequest extends FormRequest
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
                'first_name' => ['required', 'string'],
                'middle_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'address' => ['required', 'string'],
                'number' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
                'ticket_type_id' => ['required', 'exists:ticket_type,id'],
                'category_id' => ['required', 'exists:categories,id'],
                'sub_category_id' => ['required', 'exists:sub_categories,id'],
                'subject' => ['required', 'string'],
                'ref_no' => ['required', 'string'],
                'concern' => ['nullable', 'string'],
                'status' => ['nullable', 'string', Rule::in([
                    'OPEN', 'ASSIGNED', 'ON HOLD', 'CLOSED', 'CANCELLED'
                ])],
                'resolved_by' => ['nullable', 'string']
            ];
        } else {
            return [
                'status' => ['nullable', 'string', Rule::in([
                    'OPEN', 'ASSIGNED', 'ON HOLD', 'CLOSED', 'CANCELLED'
                ])],
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
