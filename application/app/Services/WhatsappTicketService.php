<?php

namespace App\Services;

use App\Models\WhatsappTicket;
use App\Models\WhatsappContact;
use App\Events\WhatsappTicketCreated;
use App\Events\WhatsappTicketStatusChanged;
use App\Events\WhatsappTicketAssigned;
use Illuminate\Support\Str;

class WhatsappTicketService
{
    /**
     * Create a new ticket
     */
    public function createTicket($data)
    {
        // Generate ticket number using model method
        $ticketNumber = WhatsappTicket::generateTicketNumber();

        $ticket = WhatsappTicket::create([
            'whatsappticket_uniqueid' => Str::uuid(),
            'whatsappticket_number' => $ticketNumber,
            'whatsappticket_contact_name' => $data['contact_name'],
            'whatsappticket_contact_email' => $data['contact_email'] ?? null,
            'whatsappticket_contact_phone' => $data['contact_phone'],
            'whatsappticket_contactid' => $data['contactid'] ?? $data['contact_id'] ?? null,
            'whatsappticket_connectionid' => $data['connectionid'] ?? $data['connection_id'] ?? null,
            'whatsappticket_clientid' => $data['clientid'] ?? $data['client_id'] ?? null,
            'whatsappticket_assigned_to' => $data['agent_id'] ?? null,
            'whatsappticket_status' => $data['status'] ?? 'open',
            'whatsappticket_channel' => $data['channel'] ?? 'whatsapp',
            'whatsappticket_subject' => $data['subject'] ?? 'WhatsApp Conversation',
            'whatsappticket_tags' => isset($data['tags']) ? json_encode($data['tags']) : null,
            'whatsappticket_priority' => $data['priority'] ?? 'normal',
            'whatsappticket_typeid' => $data['category'] ?? $data['typeid'] ?? null,
        ]);

        // Fire event for automation
        event(new WhatsappTicketCreated($ticket));

        return $ticket->fresh(['contact', 'assignedAgent']);
    }

    /**
     * Find or create ticket for a contact
     */
    public function findOrCreateForContact($contactPhone, $connectionId = null)
    {
        // Check for existing open ticket
        $ticket = WhatsappTicket::where('whatsappticket_contact_phone', $contactPhone)
            ->whereIn('whatsappticket_status', ['open', 'in_progress'])
            ->first();

        if ($ticket) {
            return $ticket;
        }

        // Find contact
        $contact = WhatsappContact::where('whatsappcontact_phone', $contactPhone)->first();

        // Create new ticket
        return $this->createTicket([
            'contact_name' => $contact?->whatsappcontact_name ?? $contactPhone,
            'contact_email' => $contact?->whatsappcontact_email,
            'contact_phone' => $contactPhone,
            'contact_id' => $contact?->whatsappcontact_id,
            'connection_id' => $connectionId,
            'status' => 'open',
            'priority' => 'normal'
        ]);
    }

    /**
     * Update ticket status
     */
    public function updateStatus($ticketId, $newStatus)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);
        $oldStatus = $ticket->whatsappticket_status;

        if ($oldStatus === $newStatus) {
            return $ticket;
        }

        $ticket->whatsappticket_status = $newStatus;

        // Update timestamps
        if ($newStatus === 'closed') {
            $ticket->whatsappticket_closed_at = now();
        } elseif ($newStatus === 'resolved') {
            $ticket->whatsappticket_resolved_at = now();
        }

        $ticket->save();

        // Fire event
        event(new WhatsappTicketStatusChanged($ticket, $oldStatus, $newStatus));

        return $ticket->fresh();
    }

    /**
     * Assign ticket to agent
     */
    public function assignTicket($ticketId, $agentId)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);
        $ticket->whatsappticket_assigned_to = $agentId;
        $ticket->save();

        // Fire event
        event(new WhatsappTicketAssigned($ticket, $ticket->assignedAgent));

        return $ticket->fresh('assignedAgent');
    }

    /**
     * Unassign ticket
     */
    public function unassignTicket($ticketId)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);
        $ticket->whatsappticket_assigned_to = null;
        $ticket->save();

        return $ticket;
    }

    /**
     * Add tags to ticket
     */
    public function addTags($ticketId, array $tags)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);
        $currentTags = $ticket->whatsappticket_tags ? json_decode($ticket->whatsappticket_tags, true) : [];
        $ticket->whatsappticket_tags = json_encode(array_unique(array_merge($currentTags, $tags)));
        $ticket->save();

        return $ticket;
    }

    /**
     * Remove tags from ticket
     */
    public function removeTags($ticketId, array $tags)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);
        $currentTags = $ticket->whatsappticket_tags ? json_decode($ticket->whatsappticket_tags, true) : [];
        $ticket->whatsappticket_tags = json_encode(array_diff($currentTags, $tags));
        $ticket->save();

        return $ticket;
    }

    /**
     * Update ticket priority
     */
    public function updatePriority($ticketId, $priority)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);
        $ticket->whatsappticket_priority = $priority;
        $ticket->save();

        return $ticket;
    }

    /**
     * Add internal note to ticket
     */
    public function addInternalNote($ticketId, $note, $userId)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);
        $currentNotes = $ticket->whatsappticket_internal_notes ?? '';

        $newNote = "[" . now()->format('Y-m-d H:i:s') . "] User #{$userId}: " . $note . "\n";
        $ticket->whatsappticket_internal_notes = $currentNotes . $newNote;
        $ticket->save();

        return $ticket;
    }

    /**
     * Generate unique ticket number (deprecated - use model method instead)
     */
    protected function generateTicketNumber()
    {
        return WhatsappTicket::generateTicketNumber();
    }

    /**
     * Get ticket statistics
     */
    public function getStatistics($filters = [])
    {
        $query = WhatsappTicket::query();

        // Apply filters
        if (isset($filters['date_from'])) {
            $query->where('whatsappticket_created', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('whatsappticket_created', '<=', $filters['date_to']);
        }

        if (isset($filters['agent_id'])) {
            $query->where('whatsappticket_assigned_to', $filters['agent_id']);
        }

        $total = $query->count();
        $open = $query->clone()->where('whatsappticket_status', 'open')->count();
        $onHold = $query->clone()->where('whatsappticket_status', 'on_hold')->count();
        $resolved = $query->clone()->where('whatsappticket_status', 'resolved')->count();
        $closed = $query->clone()->where('whatsappticket_status', 'closed')->count();

        // Calculate average response time
        $avgResponseTime = $query->clone()
            ->whereNotNull('whatsappticket_first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, whatsappticket_created, whatsappticket_first_response_at)) as avg_time')
            ->value('avg_time');

        return [
            'total' => $total,
            'open' => $open,
            'on_hold' => $onHold,
            'resolved' => $resolved,
            'closed' => $closed,
            'avg_response_time' => round($avgResponseTime ?? 0)
        ];
    }
}
