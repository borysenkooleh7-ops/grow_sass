<?php

namespace App\Services;

use App\Models\WhatsappTicket;
use App\Models\WhatsappMessage;
use App\Models\WhatsappContact;
use App\Jobs\SendWhatsappMessageJob;
use App\Events\WhatsappMessageReceived;
use App\Events\WhatsappTicketCreated;
use App\Events\WhatsappTicketStatusChanged;
use App\Events\WhatsappTicketAssigned;
use Illuminate\Support\Facades\Log;

/**
 * Integration service to bridge existing controllers with new Jobs/Events
 * This ensures backward compatibility while adding new features
 */
class WhatsappIntegrationService
{
    /**
     * Handle incoming message and fire events
     */
    public function handleIncomingMessage($messageData, $ticketData)
    {
        try {
            // Create or update ticket
            $ticket = $this->findOrCreateTicket($ticketData);

            // Create message record
            $message = WhatsappMessage::create([
                'whatsappmessage_uniqueid' => \Illuminate\Support\Str::uuid(),
                'whatsappmessage_ticketid' => $ticket->whatsappticket_id,
                'whatsappmessage_contactid' => $ticket->whatsappticket_contactid,
                'whatsappmessage_userid' => null,
                'whatsappmessage_direction' => 'incoming',
                'whatsappmessage_channel' => 'whatsapp',
                'whatsappmessage_type' => $messageData['type'] ?? 'text',
                'whatsappmessage_content' => $messageData['body'] ?? $messageData['content'] ?? '',
                'whatsappmessage_media_url' => $messageData['media_url'] ?? null,
                'whatsappmessage_media_filename' => $messageData['media_filename'] ?? null,
                'whatsappmessage_media_mime' => $messageData['media_mime'] ?? null,
                'whatsappmessage_media_size' => $messageData['media_size'] ?? null,
                'whatsappmessage_external_id' => $messageData['message_id'] ?? $messageData['external_id'] ?? null,
                'whatsappmessage_status' => 'received',
                'whatsappmessage_is_internal_note' => 0
            ]);

            // Fire event for automation
            event(new WhatsappMessageReceived($message, $ticket));

            Log::info('Incoming message handled', [
                'message_id' => $message->whatsappmessage_id,
                'ticket_id' => $ticket->whatsappticket_id
            ]);

            return [
                'success' => true,
                'message' => $message,
                'ticket' => $ticket
            ];

        } catch (\Exception $e) {
            Log::error('Failed to handle incoming message', [
                'error' => $e->getMessage(),
                'data' => $messageData
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle outgoing message and dispatch job
     */
    public function handleOutgoingMessage($messageData, $ticketId, $dispatchJob = true)
    {
        try {
            $ticket = WhatsappTicket::with('contact')->findOrFail($ticketId);

            // Create message record
            $message = WhatsappMessage::create([
                'whatsappmessage_uniqueid' => \Illuminate\Support\Str::uuid(),
                'whatsappmessage_ticketid' => $ticketId,
                'whatsappmessage_contactid' => $ticket->whatsappticket_contactid,
                'whatsappmessage_userid' => $messageData['sender_id'] ?? auth()->id(),
                'whatsappmessage_direction' => 'outgoing',
                'whatsappmessage_channel' => $messageData['channel'] ?? 'whatsapp',
                'whatsappmessage_type' => $messageData['type'] ?? 'text',
                'whatsappmessage_content' => $messageData['body'] ?? $messageData['content'] ?? '',
                'whatsappmessage_media_url' => $messageData['media_url'] ?? null,
                'whatsappmessage_media_filename' => $messageData['media_filename'] ?? null,
                'whatsappmessage_media_mime' => $messageData['media_mime'] ?? null,
                'whatsappmessage_media_size' => $messageData['media_size'] ?? null,
                'whatsappmessage_status' => 'pending',
                'whatsappmessage_is_internal_note' => $messageData['is_internal_note'] ?? 0
            ]);

            // Dispatch job for async sending (unless it's an internal note)
            if ($dispatchJob && !($messageData['is_internal_note'] ?? false)) {
                dispatch(new SendWhatsappMessageJob(
                    $message,
                    $ticket->contact ? $ticket->contact->whatsappcontact_phone : null,
                    $ticket->whatsappticket_connectionid
                ));

                Log::info('Message job dispatched', [
                    'message_id' => $message->whatsappmessage_id,
                    'ticket_id' => $ticket->whatsappticket_id
                ]);
            }

            return [
                'success' => true,
                'message' => $message
            ];

        } catch (\Exception $e) {
            Log::error('Failed to handle outgoing message', [
                'error' => $e->getMessage(),
                'data' => $messageData
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create ticket and fire event
     */
    public function createTicket($data)
    {
        try {
            // Generate ticket number using model method for consistency
            $ticketNumber = WhatsappTicket::generateTicketNumber();

            $ticket = WhatsappTicket::create([
                'whatsappticket_uniqueid' => \Illuminate\Support\Str::uuid(),
                'whatsappticket_number' => $ticketNumber,
                'whatsappticket_connectionid' => $data['connectionid'] ?? $data['connection_id'] ?? null,
                'whatsappticket_contactid' => $data['contactid'] ?? $data['contact_id'] ?? null,
                'whatsappticket_clientid' => $data['clientid'] ?? $data['client_id'] ?? null,
                'whatsappticket_assigned_to' => $data['assigned_to'] ?? $data['agent_id'] ?? null,
                'whatsappticket_typeid' => $data['typeid'] ?? $data['type_id'] ?? null,
                'whatsappticket_status' => $data['status'] ?? 'on_hold',
                'whatsappticket_priority' => $data['priority'] ?? 'medium',
                'whatsappticket_subject' => $data['subject'] ?? 'WhatsApp Conversation',
                'whatsappticket_last_message_at' => now()
            ]);

            // Fire event for automation, routing, SLA
            event(new WhatsappTicketCreated($ticket));

            Log::info('Ticket created with event', [
                'ticket_id' => $ticket->whatsappticket_id
            ]);

            return $ticket;

        } catch (\Exception $e) {
            Log::error('Failed to create ticket', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    /**
     * Update ticket status and fire event
     */
    public function updateTicketStatus($ticketId, $newStatus)
    {
        try {
            $ticket = WhatsappTicket::findOrFail($ticketId);
            $oldStatus = $ticket->whatsappticket_status;

            if ($oldStatus === $newStatus) {
                return $ticket;
            }

            $ticket->whatsappticket_status = $newStatus;

            // Update timestamps
            if ($newStatus === 'resolved') {
                $ticket->whatsappticket_resolved_at = now();
            } elseif ($newStatus === 'closed') {
                $ticket->whatsappticket_closed_at = now();
            }

            $ticket->save();

            // Fire event
            event(new WhatsappTicketStatusChanged($ticket, $oldStatus, $newStatus));

            Log::info('Ticket status updated with event', [
                'ticket_id' => $ticket->whatsappticket_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            return $ticket;

        } catch (\Exception $e) {
            Log::error('Failed to update ticket status', [
                'error' => $e->getMessage(),
                'ticket_id' => $ticketId
            ]);

            throw $e;
        }
    }

    /**
     * Assign ticket and fire event
     */
    public function assignTicket($ticketId, $agentId)
    {
        try {
            $ticket = WhatsappTicket::findOrFail($ticketId);
            $ticket->whatsappticket_assigned_to = $agentId;
            $ticket->save();

            // Fire event
            event(new WhatsappTicketAssigned($ticket, $ticket->assignedAgent));

            Log::info('Ticket assigned with event', [
                'ticket_id' => $ticket->whatsappticket_id,
                'agent_id' => $agentId
            ]);

            return $ticket;

        } catch (\Exception $e) {
            Log::error('Failed to assign ticket', [
                'error' => $e->getMessage(),
                'ticket_id' => $ticketId
            ]);

            throw $e;
        }
    }

    /**
     * Find or create ticket for contact
     */
    protected function findOrCreateTicket($data)
    {
        // Check for existing open ticket
        $ticket = WhatsappTicket::where('whatsappticket_contactid', $data['contactid'] ?? $data['contact_id'] ?? null)
            ->whereIn('whatsappticket_status', ['on_hold', 'open'])
            ->first();

        if ($ticket) {
            // Update last activity
            $ticket->whatsappticket_last_message_at = now();
            $ticket->save();
            return $ticket;
        }

        // Create new ticket
        return $this->createTicket($data);
    }

    /**
     * Mark ticket first response
     */
    public function markFirstResponse($ticketId)
    {
        $ticket = WhatsappTicket::find($ticketId);

        if ($ticket && !$ticket->whatsappticket_last_response_at) {
            $ticket->whatsappticket_last_response_at = now();
            $ticket->save();

            Log::info('First response marked', [
                'ticket_id' => $ticketId,
                'response_time' => $ticket->whatsappticket_created->diffInSeconds($ticket->whatsappticket_last_response_at)
            ]);
        }

        return $ticket;
    }

    /**
     * Get or create contact
     */
    public function getOrCreateContact($phone, $name = null, $connectionId = null)
    {
        $contact = WhatsappContact::where('whatsappcontact_phone', $phone)
            ->where('whatsappcontact_connectionid', $connectionId)
            ->first();

        if (!$contact) {
            $contact = WhatsappContact::create([
                'whatsappcontact_uniqueid' => \Illuminate\Support\Str::uuid(),
                'whatsappcontact_connectionid' => $connectionId,
                'whatsappcontact_phone' => $phone,
                'whatsappcontact_name' => $name ?? $phone,
                'whatsappcontact_display_name' => $name ?? $phone,
                'whatsappcontact_last_message_at' => now()
            ]);

            Log::info('New contact created', [
                'contact_id' => $contact->whatsappcontact_id,
                'phone' => $phone
            ]);
        }

        return $contact;
    }
}
