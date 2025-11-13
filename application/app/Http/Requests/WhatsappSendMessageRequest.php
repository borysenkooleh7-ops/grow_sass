<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappSendMessageRequest extends FormRequest
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
            'ticket_id' => 'required|integer|exists:whatsapp_tickets,id',
            'content' => 'required_without:attachments|string|max:4096',
            'channel' => 'required|in:whatsapp,email',
            'is_internal_note' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*.type' => 'required_with:attachments|in:image,document,audio,video',
            'attachments.*.url' => 'required_with:attachments|url',
            'attachments.*.filename' => 'nullable|string|max:255',
            'attachments.*.size' => 'nullable|integer|max:16777216' // 16MB
        ];

        return $rules;
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes()
    {
        return [
            'ticket_id' => 'ticket',
            'content' => 'message content',
            'channel' => 'channel',
            'is_internal_note' => 'internal note flag',
            'attachments' => 'attachments'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'ticket_id.required' => 'Ticket ID is required',
            'ticket_id.exists' => 'Ticket not found',
            'content.required_without' => 'Message content is required when no attachments are provided',
            'content.max' => 'Message cannot exceed 4096 characters',
            'channel.required' => 'Communication channel is required',
            'channel.in' => 'Invalid channel selected',
            'attachments.*.size.max' => 'Attachment size cannot exceed 16MB'
        ];
    }
}
