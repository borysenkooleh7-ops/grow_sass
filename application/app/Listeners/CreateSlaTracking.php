<?php

namespace App\Listeners;

use App\Events\WhatsappTicketCreated;
use App\Models\WhatsappSlaPolicy;
use App\Models\WhatsappTicketSla;
use Illuminate\Support\Facades\Log;

class CreateSlaTracking
{
    /**
     * Handle the event.
     */
    public function handle(WhatsappTicketCreated $event)
    {
        $ticket = $event->ticket;

        // Find applicable SLA policy based on priority
        $slaPolicy = WhatsappSlaPolicy::active()
            ->where('priority', $ticket->priority ?? 'normal')
            ->first();

        if (!$slaPolicy) {
            // Use default policy if none found for priority
            $slaPolicy = WhatsappSlaPolicy::active()
                ->where('priority', 'normal')
                ->first();
        }

        if (!$slaPolicy) {
            Log::debug('No SLA policy found for ticket', [
                'ticket_id' => $ticket->id,
                'priority' => $ticket->priority
            ]);
            return;
        }

        // Create first response SLA tracking
        $firstResponseTarget = $slaPolicy->calculateFirstResponseTarget($ticket->opened_at ?? now());

        WhatsappTicketSla::create([
            'ticket_id' => $ticket->id,
            'sla_policy_id' => $slaPolicy->id,
            'sla_type' => 'first_response',
            'target_time' => $firstResponseTarget,
            'status' => 'at_risk'
        ]);

        // Create resolution SLA tracking
        $resolutionTarget = $slaPolicy->calculateResolutionTarget($ticket->opened_at ?? now());

        WhatsappTicketSla::create([
            'ticket_id' => $ticket->id,
            'sla_policy_id' => $slaPolicy->id,
            'sla_type' => 'resolution',
            'target_time' => $resolutionTarget,
            'status' => 'at_risk'
        ]);

        Log::info('SLA tracking created for ticket', [
            'ticket_id' => $ticket->id,
            'sla_policy_id' => $slaPolicy->id,
            'first_response_target' => $firstResponseTarget,
            'resolution_target' => $resolutionTarget
        ]);
    }
}
