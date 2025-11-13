<?php

namespace App\Events;

use App\Models\WhatsappMessage;
use App\Models\WhatsappTicket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsappMessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $ticket;

    /**
     * Create a new event instance.
     */
    public function __construct(WhatsappMessage $message, WhatsappTicket $ticket)
    {
        $this->message = $message;
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('whatsapp.ticket.' . $this->ticket->id);
    }

    /**
     * Data to broadcast
     */
    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'body' => $this->message->body,
                'sender_type' => $this->message->sender_type,
                'sender_name' => $this->message->sender_name,
                'created_at' => $this->message->created_at->toIso8601String()
            ],
            'ticket' => [
                'id' => $this->ticket->id,
                'status' => $this->ticket->status
            ]
        ];
    }
}
