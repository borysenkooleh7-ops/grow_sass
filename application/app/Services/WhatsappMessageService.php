<?php

namespace App\Services;

use App\Models\WhatsappMessage;
use App\Models\WhatsappTicket;
use App\Models\WhatsappMedia;
use App\Jobs\SendWhatsappMessageJob;
use App\Events\WhatsappMessageReceived;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WhatsappMessageService
{
    /**
     * Create and send a WhatsApp message
     */
    public function sendMessage($ticketId, $data)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);

        // Create message record
        $message = WhatsappMessage::create([
            'ticket_id' => $ticketId,
            'sender_type' => $data['sender_type'] ?? 'agent',
            'sender_id' => $data['sender_id'] ?? auth()->id(),
            'sender_name' => $data['sender_name'] ?? auth()->user()->first_name,
            'channel' => $data['channel'] ?? 'whatsapp',
            'body' => $data['body'] ?? '',
            'attachments' => $data['attachments'] ?? null,
            'status' => 'pending',
            'metadata' => $data['metadata'] ?? []
        ]);

        // Handle media attachments
        if (!empty($data['attachments'])) {
            foreach ($data['attachments'] as $attachment) {
                $this->createMediaRecord($message, $ticket, $attachment);
            }
        }

        // Queue message for sending
        if (!($data['is_internal_note'] ?? false)) {
            dispatch(new SendWhatsappMessageJob(
                $message,
                $ticket->contact_phone,
                $ticket->connection_id ?? null
            ));
        }

        return [
            'success' => true,
            'message' => $message->fresh(['media'])
        ];
    }

    /**
     * Store incoming WhatsApp message
     */
    public function storeIncomingMessage($ticketId, $data)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);

        $message = WhatsappMessage::create([
            'ticket_id' => $ticketId,
            'sender_type' => 'client',
            'sender_id' => null,
            'sender_name' => $data['sender_name'] ?? $ticket->contact_name,
            'channel' => 'whatsapp',
            'body' => $data['body'] ?? '',
            'message_id' => $data['message_id'] ?? null,
            'status' => 'received',
            'attachments' => $data['attachments'] ?? null,
            'metadata' => $data['metadata'] ?? []
        ]);

        // Handle media attachments
        if (!empty($data['attachments'])) {
            foreach ($data['attachments'] as $attachment) {
                $this->createMediaRecord($message, $ticket, $attachment);
            }
        }

        // Fire event for automation
        event(new WhatsappMessageReceived($message, $ticket));

        // Update ticket last contact
        $ticket->touch();

        return $message;
    }

    /**
     * Create media record
     */
    protected function createMediaRecord($message, $ticket, $attachment)
    {
        return WhatsappMedia::create([
            'message_id' => $message->id,
            'ticket_id' => $ticket->id,
            'type' => $attachment['type'],
            'filename' => $attachment['filename'] ?? 'file',
            'extension' => $attachment['extension'] ?? pathinfo($attachment['filename'] ?? '', PATHINFO_EXTENSION),
            'mime_type' => $attachment['mime_type'] ?? 'application/octet-stream',
            'size' => $attachment['size'] ?? 0,
            'url' => $attachment['url'],
            'thumbnail_url' => $attachment['thumbnail_url'] ?? null,
            'sender_type' => $message->sender_type,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender_name
        ]);
    }

    /**
     * Upload file and return URL
     */
    public function uploadFile($file)
    {
        try {
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = 'whatsapp/media/' . date('Y/m');

            // Store file
            $file->move(public_path($path), $filename);

            $url = '/' . $path . '/' . $filename;

            // Determine file type
            $mimeType = $file->getMimeType();
            $type = 'document';

            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $type = 'video';
            } elseif (str_starts_with($mimeType, 'audio/')) {
                $type = 'audio';
            }

            return [
                'success' => true,
                'type' => $type,
                'url' => $url,
                'filename' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $mimeType,
                'size' => $file->getSize()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($ticketId)
    {
        return WhatsappMessage::where('ticket_id', $ticketId)
            ->where('sender_type', 'client')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Get messages for a ticket
     */
    public function getMessages($ticketId, $limit = 50, $offset = 0)
    {
        return WhatsappMessage::where('ticket_id', $ticketId)
            ->with(['media', 'sender'])
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Get new messages since a specific message ID
     */
    public function getNewMessagesSince($ticketId, $lastMessageId)
    {
        return WhatsappMessage::where('ticket_id', $ticketId)
            ->where('id', '>', $lastMessageId)
            ->with(['media', 'sender'])
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
