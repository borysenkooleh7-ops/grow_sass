<?php

/**
 * @fileoverview WhatsApp Message Repository
 * @description Business logic for WhatsApp messages
 */

namespace App\Repositories;

use App\Models\WhatsappMessage;
use App\Models\WhatsappTicket;
use Illuminate\Support\Str;

class WhatsappMessageRepository
{
    /**
     * Create a new message
     */
    public function create($data)
    {
        // Validate required fields
        if (empty($data['ticketid']) || empty($data['contactid']) || empty($data['direction'])) {
            return null;
        }

        // If sending via WhatsApp, check 24-hour window
        if (($data['channel'] ?? 'whatsapp') === 'whatsapp' && $data['direction'] === 'outgoing') {
            $ticket = WhatsappTicket::find($data['ticketid']);
            if ($ticket && !$ticket->isWithin24HourWindow()) {
                // Cannot send via WhatsApp outside 24-hour window
                return [
                    'success' => false,
                    'error' => 'Cannot send WhatsApp message outside 24-hour window. Please use Email instead.',
                    'code' => 'WINDOW_EXPIRED',
                ];
            }
        }

        $message = new WhatsappMessage();
        $message->whatsappmessage_uniqueid = Str::uuid();
        $message->whatsappmessage_ticketid = $data['ticketid'];
        $message->whatsappmessage_contactid = $data['contactid'];
        $message->whatsappmessage_userid = $data['userid'] ?? null;
        $message->whatsappmessage_direction = $data['direction'];
        $message->whatsappmessage_channel = $data['channel'] ?? 'whatsapp';
        $message->whatsappmessage_type = $data['type'] ?? 'text';
        $message->whatsappmessage_content = $data['content'];
        $message->whatsappmessage_media_url = $data['media_url'] ?? null;
        $message->whatsappmessage_media_filename = $data['media_filename'] ?? null;
        $message->whatsappmessage_media_mime = $data['media_mime'] ?? null;
        $message->whatsappmessage_media_size = $data['media_size'] ?? null;
        $message->whatsappmessage_status = $data['status'] ?? 'pending';
        $message->whatsappmessage_external_id = $data['external_id'] ?? null;
        $message->whatsappmessage_is_internal_note = $data['is_internal_note'] ?? 0;

        if ($message->save()) {
            // Update ticket's last message timestamp
            $this->updateTicketTimestamps($message->whatsappmessage_ticketid, $message->whatsappmessage_direction);

            return [
                'success' => true,
                'message' => $message,
            ];
        }

        return [
            'success' => false,
            'error' => 'Failed to save message',
        ];
    }

    /**
     * Store incoming message from webhook
     */
    public function storeIncoming($ticketId, $contactId, $content, $externalId, $type = 'text', $mediaData = null)
    {
        $data = [
            'ticketid' => $ticketId,
            'contactid' => $contactId,
            'direction' => 'incoming',
            'channel' => 'whatsapp',
            'type' => $type,
            'content' => $content,
            'status' => 'delivered',
            'external_id' => $externalId,
        ];

        // Add media data if provided
        if ($mediaData) {
            $data = array_merge($data, $mediaData);
        }

        return $this->create($data);
    }

    /**
     * Get messages for a ticket
     */
    public function getByTicket($ticketId, $limit = 100)
    {
        return WhatsappMessage::where('whatsappmessage_ticketid', $ticketId)
            ->with(['user', 'contact'])
            ->orderBy('whatsappmessage_created', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get messages after a specific message ID (for polling)
     */
    public function getNewMessages($ticketId, $afterMessageId)
    {
        return WhatsappMessage::where('whatsappmessage_ticketid', $ticketId)
            ->where('whatsappmessage_id', '>', $afterMessageId)
            ->with(['user', 'contact'])
            ->orderBy('whatsappmessage_created', 'asc')
            ->get();
    }

    /**
     * Update ticket timestamps when message is created
     */
    private function updateTicketTimestamps($ticketId, $direction)
    {
        $ticket = WhatsappTicket::find($ticketId);

        if (!$ticket) {
            return false;
        }

        $ticket->whatsappticket_last_message_at = now();

        if ($direction === 'outgoing') {
            $ticket->whatsappticket_last_response_at = now();
        }

        if ($direction === 'incoming') {
            // Increment unread count for incoming messages
            $ticket->whatsappticket_unread_count = ($ticket->whatsappticket_unread_count ?? 0) + 1;
        }

        return $ticket->save();
    }

    /**
     * Mark ticket messages as read
     */
    public function markTicketAsRead($ticketId)
    {
        // Mark all incoming messages as read
        WhatsappMessage::where('whatsappmessage_ticketid', $ticketId)
            ->where('whatsappmessage_direction', 'incoming')
            ->where('whatsappmessage_status', '!=', 'read')
            ->update(['whatsappmessage_status' => 'read']);

        // Reset ticket unread count
        $ticket = WhatsappTicket::find($ticketId);
        if ($ticket) {
            $ticket->whatsappticket_unread_count = 0;
            $ticket->save();
        }

        return true;
    }

    /**
     * Update message status (for webhook updates)
     */
    public function updateStatus($externalId, $status)
    {
        $message = WhatsappMessage::where('whatsappmessage_external_id', $externalId)->first();

        if ($message) {
            $message->whatsappmessage_status = $status;
            return $message->save();
        }

        return false;
    }

    /**
     * Handle file upload for message
     */
    public function handleFileUpload($file)
    {
        if (!$file || !$file->isValid()) {
            return [
                'success' => false,
                'error' => 'Invalid file upload',
            ];
        }

        // Validate file size (max 16MB for WhatsApp)
        if ($file->getSize() > 16 * 1024 * 1024) {
            return [
                'success' => false,
                'error' => 'File size exceeds 16MB limit',
            ];
        }

        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('whatsapp/media', $filename, 'public');

        if (!$path) {
            return [
                'success' => false,
                'error' => 'Failed to store file',
            ];
        }

        return [
            'success' => true,
            'media_url' => '/storage/' . $path,
            'media_filename' => $file->getClientOriginalName(),
            'media_mime' => $file->getMimeType(),
            'media_size' => $file->getSize(),
            'type' => $this->getMessageTypeFromMime($file->getMimeType()),
        ];
    }

    /**
     * Determine message type from MIME type
     */
    private function getMessageTypeFromMime($mime)
    {
        if (strpos($mime, 'image/') === 0) {
            return 'image';
        } else if (strpos($mime, 'video/') === 0) {
            return 'video';
        } else if (strpos($mime, 'audio/') === 0) {
            return 'audio';
        } else {
            return 'document';
        }
    }

    /**
     * Search messages
     */
    public function search($query, $ticketId = null, $limit = 50)
    {
        $messages = WhatsappMessage::where('whatsappmessage_content', 'like', "%{$query}%");

        if ($ticketId) {
            $messages->where('whatsappmessage_ticketid', $ticketId);
        }

        return $messages->with(['ticket', 'contact', 'user'])
            ->orderBy('whatsappmessage_created', 'desc')
            ->limit($limit)
            ->get();
    }
}
