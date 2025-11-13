<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $contactId = $this->route('id');

        $rules = [
            'name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:50',
                'regex:/^[+]?[0-9\s\-()]+$/',
                'unique:whatsapp_contacts,phone_number' . ($contactId ? ',' . $contactId : '')
            ],
            'email' => 'nullable|email|max:255',
            'avatar_url' => 'nullable|url|max:500',
            'client_id' => 'nullable|exists:clients,client_id',
            'language' => 'nullable|string|size:2',
            'timezone' => 'nullable|timezone',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.key' => 'required_with:custom_fields|string|max:50',
            'custom_fields.*.value' => 'required_with:custom_fields|string|max:500'
        ];

        return $rules;
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes()
    {
        return [
            'name' => 'contact name',
            'phone_number' => 'phone number',
            'email' => 'email address',
            'avatar_url' => 'avatar URL',
            'client_id' => 'linked client',
            'language' => 'language code',
            'timezone' => 'timezone',
            'custom_fields' => 'custom fields'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'name.required' => 'Contact name is required',
            'phone_number.required' => 'Phone number is required',
            'phone_number.regex' => 'Phone number format is invalid',
            'phone_number.unique' => 'This phone number already exists',
            'email.email' => 'Please enter a valid email address',
            'language.size' => 'Language code must be 2 characters (e.g., en, es)',
            'timezone.timezone' => 'Please enter a valid timezone'
        ];
    }
}
