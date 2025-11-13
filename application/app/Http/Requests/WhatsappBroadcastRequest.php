<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappBroadcastRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:4096',
            'recipient_type' => 'required|in:all_contacts,clients,specific_clients,tags,custom',
            'recipient_data' => 'nullable|array',
            'recipient_data.client_ids' => 'required_if:recipient_type,specific_clients|array',
            'recipient_data.client_ids.*' => 'exists:clients,client_id',
            'recipient_data.contacts' => 'required_if:recipient_type,custom|array',
            'recipient_data.contacts.*.phone' => 'required|string',
            'recipient_data.contacts.*.name' => 'nullable|string',
            'connection_id' => 'required|exists:whatsapp_connections,id',
            'template_id' => 'nullable|exists:whatsapp_templates,id',
            'attachments' => 'nullable|array',
            'attachments.*.type' => 'required_with:attachments|in:image,document,audio,video',
            'attachments.*.url' => 'required_with:attachments|url',
            'attachments.*.filename' => 'nullable|string',
            'scheduled_at' => 'nullable|date|after:now'
        ];

        return $rules;
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes()
    {
        return [
            'name' => 'broadcast name',
            'message' => 'broadcast message',
            'recipient_type' => 'recipient type',
            'recipient_data' => 'recipient data',
            'connection_id' => 'WhatsApp connection',
            'template_id' => 'message template',
            'attachments' => 'attachments',
            'scheduled_at' => 'scheduled date/time'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'name.required' => 'Broadcast name is required',
            'message.required' => 'Broadcast message is required',
            'message.max' => 'Message cannot exceed 4096 characters',
            'recipient_type.required' => 'Please select recipient type',
            'connection_id.required' => 'Please select a WhatsApp connection',
            'connection_id.exists' => 'Selected connection does not exist',
            'scheduled_at.after' => 'Scheduled time must be in the future'
        ];
    }
}
