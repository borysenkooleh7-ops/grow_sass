<?php

/**
 * @fileoverview WhatsApp Auto-Assignment Service
 * @description Handles automatic ticket assignment to agents using various strategies
 */

namespace App\Services;

use App\Models\WhatsappTicket;
use App\Models\WhatsappLineConfig;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WhatsappAutoAssignmentService
{
    /**
     * Auto-assign a ticket based on line configuration
     *
     * @param WhatsappTicket $ticket
     * @return bool
     */
    public function autoAssignTicket($ticket)
    {
        try {
            // Get line configuration for this connection
            $lineConfig = WhatsappLineConfig::where('whatsapplineconfig_connectionid', $ticket->whatsappticket_connectionid)->first();

            if (!$lineConfig || !$lineConfig->whatsapplineconfig_auto_assign_enabled) {
                Log::info('Auto-assignment disabled for ticket', ['ticket_id' => $ticket->whatsappticket_id]);
                return false;
            }

            // Get assignment logic
            $logic = $lineConfig->whatsapplineconfig_auto_assign_logic ?? 'round_robin';

            // Get available agents (team members who are online/active)
            $availableAgents = $this->getAvailableAgents();

            if ($availableAgents->isEmpty()) {
                Log::warning('No available agents for auto-assignment', ['ticket_id' => $ticket->whatsappticket_id]);
                return false;
            }

            // Select agent based on logic
            $selectedAgent = null;
            switch ($logic) {
                case 'round_robin':
                    $selectedAgent = $this->roundRobinAssignment($availableAgents, $ticket->whatsappticket_connectionid);
                    break;

                case 'least_active':
                    $selectedAgent = $this->leastActiveAssignment($availableAgents);
                    break;

                case 'random':
                    $selectedAgent = $availableAgents->random();
                    break;

                default:
                    $selectedAgent = $this->roundRobinAssignment($availableAgents, $ticket->whatsappticket_connectionid);
            }

            if ($selectedAgent) {
                $ticket->whatsappticket_assigned_to = $selectedAgent->id;
                $ticket->whatsappticket_status = 'open';
                $ticket->save();

                Log::info('Ticket auto-assigned', [
                    'ticket_id' => $ticket->whatsappticket_id,
                    'agent_id' => $selectedAgent->id,
                    'logic' => $logic,
                ]);

                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Auto-assignment error', [
                'ticket_id' => $ticket->whatsappticket_id ?? null,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get available agents for assignment
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAvailableAgents()
    {
        // Get team members who have access to WhatsApp module
        // You can customize this query based on your permissions system
        return User::where('type', 'team')
            ->where('status', 'active')
            ->get();
    }

    /**
     * Round-robin assignment strategy
     * Assigns tickets evenly to all available agents in rotation
     *
     * @param \Illuminate\Database\Eloquent\Collection $agents
     * @param int $connectionId
     * @return User|null
     */
    protected function roundRobinAssignment($agents, $connectionId)
    {
        $cacheKey = "whatsapp_roundrobin_index_{$connectionId}";

        // Get current index from cache
        $currentIndex = Cache::get($cacheKey, 0);

        // Get agent at current index
        $agentsList = $agents->values(); // Reset keys to 0-indexed array
        $selectedAgent = $agentsList->get($currentIndex % $agentsList->count());

        // Increment index for next assignment
        $nextIndex = ($currentIndex + 1) % $agentsList->count();
        Cache::put($cacheKey, $nextIndex, now()->addDays(7));

        return $selectedAgent;
    }

    /**
     * Least active assignment strategy
     * Assigns ticket to agent with fewest open tickets
     *
     * @param \Illuminate\Database\Eloquent\Collection $agents
     * @return User|null
     */
    protected function leastActiveAssignment($agents)
    {
        $agentWorkload = [];

        foreach ($agents as $agent) {
            // Count open tickets assigned to this agent
            $openTickets = WhatsappTicket::where('whatsappticket_assigned_to', $agent->id)
                ->whereIn('whatsappticket_status', ['on_hold', 'open'])
                ->count();

            $agentWorkload[$agent->id] = $openTickets;
        }

        // Sort agents by workload (ascending)
        asort($agentWorkload);

        // Get agent with least workload
        $leastBusyAgentId = array_key_first($agentWorkload);

        return $agents->firstWhere('id', $leastBusyAgentId);
    }

    /**
     * Check if ticket should be auto-assigned based on business hours
     *
     * @param WhatsappLineConfig $lineConfig
     * @return bool
     */
    protected function isWithinBusinessHours($lineConfig)
    {
        if (!$lineConfig->whatsapplineconfig_business_hours_enabled) {
            return true; // Always auto-assign if business hours not configured
        }

        $now = now();
        $currentDay = strtolower($now->format('l')); // monday, tuesday, etc.
        $currentTime = $now->format('H:i:s');

        // Check if today is a business day
        $businessDays = explode(',', $lineConfig->whatsapplineconfig_business_days ?? '');
        if (!in_array($currentDay, $businessDays)) {
            return false;
        }

        // Check if current time is within business hours
        if ($currentTime < $lineConfig->whatsapplineconfig_business_hours_start ||
            $currentTime > $lineConfig->whatsapplineconfig_business_hours_end) {
            return false;
        }

        return true;
    }

    /**
     * Re-assign ticket to different agent
     *
     * @param WhatsappTicket $ticket
     * @param int|null $excludeAgentId Agent to exclude from assignment
     * @return bool
     */
    public function reassignTicket($ticket, $excludeAgentId = null)
    {
        $availableAgents = $this->getAvailableAgents();

        if ($excludeAgentId) {
            $availableAgents = $availableAgents->reject(function ($agent) use ($excludeAgentId) {
                return $agent->id == $excludeAgentId;
            });
        }

        if ($availableAgents->isEmpty()) {
            return false;
        }

        // Use least active logic for re-assignment
        $selectedAgent = $this->leastActiveAssignment($availableAgents);

        if ($selectedAgent) {
            $ticket->whatsappticket_assigned_to = $selectedAgent->id;
            $ticket->save();

            Log::info('Ticket re-assigned', [
                'ticket_id' => $ticket->whatsappticket_id,
                'from_agent' => $excludeAgentId,
                'to_agent' => $selectedAgent->id,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Get agent workload statistics
     *
     * @return array
     */
    public function getAgentWorkloadStats()
    {
        $agents = $this->getAvailableAgents();
        $stats = [];

        foreach ($agents as $agent) {
            $stats[] = [
                'agent_id' => $agent->id,
                'agent_name' => $agent->first_name . ' ' . $agent->last_name,
                'open_tickets' => WhatsappTicket::where('whatsappticket_assigned_to', $agent->id)
                    ->whereIn('whatsappticket_status', ['on_hold', 'open'])
                    ->count(),
                'total_tickets' => WhatsappTicket::where('whatsappticket_assigned_to', $agent->id)
                    ->count(),
            ];
        }

        return $stats;
    }
}
