<?php

namespace App\Listeners;

use App\Events\WhatsappMessageReceived;
use App\Events\WhatsappTicketCreated;
use App\Events\WhatsappTicketStatusChanged;
use App\Events\WhatsappTicketAssigned;
use App\Models\WhatsappAutomationRule;
use App\Jobs\ProcessAutomationRuleJob;
use Illuminate\Support\Facades\Log;

class ProcessAutomationRules
{
    /**
     * Handle message received event
     */
    public function handleMessageReceived(WhatsappMessageReceived $event)
    {
        $this->triggerRules('new_message', [
            'ticket_id' => $event->ticket->id,
            'message_id' => $event->message->id,
            'message_body' => $event->message->body,
            'sender_type' => $event->message->sender_type,
            'ticket_status' => $event->ticket->status,
            'contact_phone' => $event->ticket->contact_phone,
        ]);

        // Check for keyword matches
        $this->triggerKeywordRules($event->message->body, [
            'ticket_id' => $event->ticket->id,
            'message_id' => $event->message->id,
        ]);
    }

    /**
     * Handle ticket created event
     */
    public function handleTicketCreated(WhatsappTicketCreated $event)
    {
        $this->triggerRules('new_ticket', [
            'ticket_id' => $event->ticket->id,
            'contact_phone' => $event->ticket->contact_phone,
            'contact_name' => $event->ticket->contact_name,
            'priority' => $event->ticket->priority,
            'category' => $event->ticket->category,
        ]);
    }

    /**
     * Handle ticket status changed event
     */
    public function handleStatusChanged(WhatsappTicketStatusChanged $event)
    {
        $this->triggerRules('ticket_status_change', [
            'ticket_id' => $event->ticket->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
            'contact_phone' => $event->ticket->contact_phone,
        ]);
    }

    /**
     * Handle ticket assigned event
     */
    public function handleTicketAssigned(WhatsappTicketAssigned $event)
    {
        $this->triggerRules('ticket_assigned', [
            'ticket_id' => $event->ticket->id,
            'agent_id' => $event->agent?->id,
            'agent_name' => $event->agent?->first_name . ' ' . $event->agent?->last_name,
            'contact_phone' => $event->ticket->contact_phone,
        ]);
    }

    /**
     * Trigger automation rules for a specific type
     */
    protected function triggerRules($triggerType, $data)
    {
        $rules = WhatsappAutomationRule::active()
            ->where('trigger_type', $triggerType)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($rules as $rule) {
            // Dispatch job to process rule
            dispatch(new ProcessAutomationRuleJob($rule, $data));

            // If stop_processing is enabled, don't process further rules
            if ($rule->stop_processing) {
                break;
            }
        }
    }

    /**
     * Trigger keyword-based rules
     */
    protected function triggerKeywordRules($messageBody, $data)
    {
        $rules = WhatsappAutomationRule::active()
            ->where('trigger_type', 'keyword_match')
            ->get();

        foreach ($rules as $rule) {
            $conditions = $rule->trigger_conditions;

            if (isset($conditions['keywords']) && is_array($conditions['keywords'])) {
                foreach ($conditions['keywords'] as $keyword) {
                    if (stripos($messageBody, $keyword) !== false) {
                        dispatch(new ProcessAutomationRuleJob($rule, array_merge($data, [
                            'matched_keyword' => $keyword,
                            'message_body' => $messageBody
                        ])));

                        if ($rule->stop_processing) {
                            return;
                        }
                    }
                }
            }
        }
    }
}
