<?php

namespace App\Listeners;

use App\Events\WhatsappTicketCreated;
use App\Models\WhatsappRoutingRule;
use Illuminate\Support\Facades\Log;

class ApplyRoutingRules
{
    /**
     * Handle the event.
     */
    public function handle(WhatsappTicketCreated $event)
    {
        $ticket = $event->ticket;

        // Skip if ticket is already assigned
        if ($ticket->agent_id) {
            return;
        }

        $rules = WhatsappRoutingRule::active()
            ->byPriority()
            ->get();

        $data = [
            'contact_phone' => $ticket->contact_phone,
            'contact_name' => $ticket->contact_name,
            'priority' => $ticket->priority,
            'category' => $ticket->category,
            'tags' => $ticket->tags,
        ];

        foreach ($rules as $rule) {
            if ($rule->matchesConditions($data)) {
                // Apply routing
                if ($rule->assign_to_type === 'user' && $rule->assign_to_id) {
                    $ticket->agent_id = $rule->assign_to_id;
                    $ticket->save();

                    Log::info('Routing rule applied', [
                        'ticket_id' => $ticket->id,
                        'rule_id' => $rule->id,
                        'assigned_to' => $rule->assign_to_id
                    ]);

                    break;
                } elseif ($rule->assign_to_type === 'auto') {
                    // TODO: Implement auto-assignment logic (round-robin, least busy, etc.)
                    Log::info('Auto-assignment triggered', [
                        'ticket_id' => $ticket->id,
                        'rule_id' => $rule->id
                    ]);

                    break;
                }
            }
        }
    }
}
