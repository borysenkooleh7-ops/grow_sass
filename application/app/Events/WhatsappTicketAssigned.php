<?php

namespace App\Events;

use App\Models\WhatsappTicket;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsappTicketAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $agent;

    /**
     * Create a new event instance.
     */
    public function __construct(WhatsappTicket $ticket, ?User $agent)
    {
        $this->ticket = $ticket;
        $this->agent = $agent;
    }
}
