<?php

namespace App\Listeners;

use App\Events\WhatsappMessageReceived;
use App\Models\WhatsappChatbotFlow;
use App\Models\WhatsappChatbotSession;
use App\Jobs\ProcessChatbotSessionJob;
use Illuminate\Support\Facades\Log;

class HandleChatbotInteraction
{
    /**
     * Handle the event.
     */
    public function handle(WhatsappMessageReceived $event)
    {
        $message = $event->message;
        $ticket = $event->ticket;

        // Only process client messages
        if ($message->sender_type !== 'client') {
            return;
        }

        // Check if there's an active chatbot session for this ticket
        $session = WhatsappChatbotSession::where('ticket_id', $ticket->id)
            ->where('status', 'active')
            ->first();

        if ($session) {
            // Continue existing session
            dispatch(new ProcessChatbotSessionJob($session, $message->body));
            return;
        }

        // Check if message should trigger a new chatbot flow
        $flows = WhatsappChatbotFlow::active()->get();

        foreach ($flows as $flow) {
            $context = [
                'unassigned' => empty($ticket->agent_id),
                'outside_hours' => !$this->isWithinBusinessHours()
            ];

            if ($flow->matchesTrigger($message->body, $context)) {
                // Start new chatbot session
                $session = WhatsappChatbotSession::create([
                    'ticket_id' => $ticket->id,
                    'flow_id' => $flow->id,
                    'current_step_id' => null,
                    'context' => [],
                    'status' => 'active',
                    'started_at' => now()
                ]);

                $flow->incrementTriggered();

                dispatch(new ProcessChatbotSessionJob($session));

                Log::info('Chatbot session started', [
                    'session_id' => $session->id,
                    'flow_id' => $flow->id,
                    'ticket_id' => $ticket->id
                ]);

                break;
            }
        }
    }

    /**
     * Check if within business hours
     */
    protected function isWithinBusinessHours()
    {
        // TODO: Implement business hours check
        return true;
    }
}
