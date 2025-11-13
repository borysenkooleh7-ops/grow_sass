<?php

namespace App\Events;

use App\Models\WhatsappTicketSla;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsappSlaBreached
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sla;

    /**
     * Create a new event instance.
     */
    public function __construct(WhatsappTicketSla $sla)
    {
        $this->sla = $sla;
    }
}
