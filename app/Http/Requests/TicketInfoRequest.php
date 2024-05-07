<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketInfoRequest extends FormRequest
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
                'ticket_id' => ['nullable'],
                'first_name' => ['required', 'string'],
                'middle_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'address' => ['required', 'string'],
                'number' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
                'ticket_type_id' => [
                    'required', 'integer',
                    'exists:ticket_type,id'
                ],
                'category_id' => [
                    'required', 'integer',
                    'exists:categories,id'
                ],
                'sub_category_id' => [
                    'required', 'integer',
                    'exists:sub_categories,id'
                ],
                'subject' => ['required', 'string'],
                'ref_no' => ['required', 'string'],
                'concern' => ['nullable', 'string'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
            ];
        } else {
            return [];
        }
    }
}
