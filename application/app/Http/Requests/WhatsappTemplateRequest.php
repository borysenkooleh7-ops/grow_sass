<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappTemplateRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'category' => 'required|in:general,marketing,notification,support,transactional',
            'message' => 'required|string|max:4096',
            'language' => 'nullable|string|max:10',
            'buttons' => 'nullable|array',
            'buttons.*.type' => 'required_with:buttons|in:url,call,quick_reply',
            'buttons.*.text' => 'required_with:buttons|string|max:20',
            'buttons.*.value' => 'required_with:buttons|string|max:500',
            'variables' => 'nullable|array',
            'variables.*' => 'string|max:50',
            'is_active' => 'boolean'
        ];

        return $rules;
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes()
    {
        return [
            'title' => 'template title',
            'category' => 'template category',
            'message' => 'template message',
            'language' => 'language code',
            'buttons' => 'template buttons',
            'variables' => 'template variables',
            'is_active' => 'active status'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'title.required' => 'Template title is required',
            'category.required' => 'Please select a template category',
            'category.in' => 'Invalid template category selected',
            'message.required' => 'Template message is required',
            'message.max' => 'Template message cannot exceed 4096 characters',
        ];
    }
}
