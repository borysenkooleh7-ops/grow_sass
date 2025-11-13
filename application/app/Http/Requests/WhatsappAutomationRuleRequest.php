<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappAutomationRuleRequest extends FormRequest
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
            'description' => 'nullable|string|max:1000',
            'trigger_type' => 'required|in:new_message,new_ticket,ticket_status_change,keyword_match,business_hours,no_response,ticket_assigned',
            'trigger_conditions' => 'nullable|array',
            'trigger_conditions.*.field' => 'required_with:trigger_conditions|string',
            'trigger_conditions.*.operator' => 'required_with:trigger_conditions|in:=,!=,contains,not_contains,starts_with,ends_with,in,not_in',
            'trigger_conditions.*.value' => 'required_with:trigger_conditions',
            'actions' => 'required|array|min:1',
            'actions.*.type' => 'required|in:send_message,assign_ticket,change_status,add_tag,send_notification,create_task,trigger_webhook',
            'actions.*.message' => 'required_if:actions.*.type,send_message|string',
            'actions.*.assign_to' => 'required_if:actions.*.type,assign_ticket|integer',
            'actions.*.status' => 'required_if:actions.*.type,change_status|in:open,in_progress,closed',
            'actions.*.webhook_url' => 'required_if:actions.*.type,trigger_webhook|url',
            'is_active' => 'boolean',
            'stop_processing' => 'boolean'
        ];

        return $rules;
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes()
    {
        return [
            'name' => 'rule name',
            'description' => 'rule description',
            'trigger_type' => 'trigger type',
            'trigger_conditions' => 'trigger conditions',
            'actions' => 'actions',
            'is_active' => 'active status',
            'stop_processing' => 'stop processing flag'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'name.required' => 'Rule name is required',
            'trigger_type.required' => 'Please select a trigger type',
            'actions.required' => 'At least one action is required',
            'actions.min' => 'At least one action must be defined',
            'actions.*.type.required' => 'Action type is required',
            'actions.*.message.required_if' => 'Message is required for send_message action',
            'actions.*.webhook_url.url' => 'Webhook URL must be a valid URL'
        ];
    }
}
