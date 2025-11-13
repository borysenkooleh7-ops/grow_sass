<?php

/**
 * @fileoverview WhatsApp Ticket Model
 * @description Represents a WhatsApp support ticket linked to a Task
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTicket extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'whatsapp_tickets';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsappticket_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsappticket_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsappticket_updated';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'whatsappticket_uniqueid',
        'whatsappticket_number',
        'whatsappticket_taskid',
        'whatsappticket_connectionid',
        'whatsappticket_contactid',
        'whatsappticket_clientid',
        'whatsappticket_assigned_to',
        'whatsappticket_typeid',
        'whatsappticket_status',
        'whatsappticket_priority',
        'whatsappticket_subject',
        'whatsappticket_unread_count',
        'whatsappticket_last_message_at',
        'whatsappticket_last_response_at',
        'whatsappticket_resolved_at',
        'whatsappticket_closed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'whatsappticket_last_message_at' => 'datetime',
        'whatsappticket_last_response_at' => 'datetime',
        'whatsappticket_resolved_at' => 'datetime',
        'whatsappticket_closed_at' => 'datetime',
        'whatsappticket_created' => 'datetime',
        'whatsappticket_updated' => 'datetime',
        'whatsappticket_unread_count' => 'integer',
        'whatsappticket_taskid' => 'integer',
        'whatsappticket_connectionid' => 'integer',
        'whatsappticket_contactid' => 'integer',
        'whatsappticket_clientid' => 'integer',
        'whatsappticket_assigned_to' => 'integer',
        'whatsappticket_typeid' => 'integer',
    ];

    /**
     * Relationship: Ticket belongs to a Connection
     */
    public function connection()
    {
        return $this->belongsTo('App\Models\WhatsappConnection', 'whatsappticket_connectionid', 'whatsappconnection_id');
    }

    /**
     * Relationship: Ticket belongs to a Contact
     */
    public function contact()
    {
        return $this->belongsTo('App\Models\WhatsappContact', 'whatsappticket_contactid', 'whatsappcontact_id');
    }

    /**
     * Relationship: Ticket may belong to a Client
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'whatsappticket_clientid', 'client_id');
    }

    /**
     * Relationship: Ticket may be assigned to an Agent (User)
     */
    public function assignedAgent()
    {
        return $this->belongsTo('App\Models\User', 'whatsappticket_assigned_to', 'id');
    }

    /**
     * Relationship: Ticket may be linked to a Task
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task', 'whatsappticket_taskid', 'task_id');
    }

    /**
     * Relationship: Ticket belongs to a Type
     */
    public function ticketType()
    {
        return $this->belongsTo('App\Models\WhatsappTicketType', 'whatsappticket_typeid', 'whatsapptickettype_id');
    }

    /**
     * Relationship: Ticket has many Messages
     */
    public function messages()
    {
        return $this->hasMany('App\Models\WhatsappMessage', 'whatsappmessage_ticketid', 'whatsappticket_id')
            ->orderBy('whatsappmessage_created', 'asc');
    }

    /**
     * Scope: Get open tickets
     */
    public function scopeOpen($query)
    {
        return $query->where('whatsappticket_status', 'open');
    }

    /**
     * Scope: Get on hold tickets
     */
    public function scopeOnHold($query)
    {
        return $query->where('whatsappticket_status', 'on_hold');
    }

    /**
     * Scope: Get resolved tickets
     */
    public function scopeResolved($query)
    {
        return $query->where('whatsappticket_status', 'resolved');
    }

    /**
     * Scope: Get closed tickets
     */
    public function scopeClosed($query)
    {
        return $query->where('whatsappticket_status', 'closed');
    }

    /**
     * Scope: Get tickets assigned to specific user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('whatsappticket_assigned_to', $userId);
    }

    /**
     * Scope: Get unassigned tickets
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('whatsappticket_assigned_to');
    }

    /**
     * Assign ticket to a user
     */
    public function assignTo($userId)
    {
        $this->whatsappticket_assigned_to = $userId;
        return $this->save();
    }

    /**
     * Change ticket status
     */
    public function changeStatus($status)
    {
        $this->whatsappticket_status = $status;

        if ($status === 'resolved') {
            $this->whatsappticket_resolved_at = now();
        } elseif ($status === 'closed') {
            $this->whatsappticket_closed_at = now();
        }

        return $this->save();
    }

    /**
     * Mark ticket as resolved
     */
    public function markAsResolved()
    {
        return $this->changeStatus('resolved');
    }

    /**
     * Mark ticket as closed
     */
    public function markAsClosed($typeId = null)
    {
        if ($typeId) {
            $this->whatsappticket_typeid = $typeId;
        }
        return $this->changeStatus('closed');
    }

    /**
     * Increment unread count
     */
    public function incrementUnreadCount()
    {
        $this->whatsappticket_unread_count = ($this->whatsappticket_unread_count ?? 0) + 1;
        return $this->save();
    }

    /**
     * Reset unread count
     */
    public function resetUnreadCount()
    {
        $this->whatsappticket_unread_count = 0;
        return $this->save();
    }

    /**
     * Update last message timestamp
     */
    public function updateLastMessageAt()
    {
        $this->whatsappticket_last_message_at = now();
        return $this->save();
    }

    /**
     * Get the last incoming message
     */
    public function getLastIncomingMessage()
    {
        return $this->messages()
            ->where('whatsappmessage_direction', 'incoming')
            ->orderBy('whatsappmessage_created', 'desc')
            ->first();
    }

    /**
     * Check if ticket is within 24-hour WhatsApp response window
     */
    public function isWithin24HourWindow()
    {
        $lastIncoming = $this->getLastIncomingMessage();

        if (!$lastIncoming) {
            return false;
        }

        return $lastIncoming->whatsappmessage_created->gt(now()->subHours(24));
    }

    /**
     * Generate unique ticket number
     */
    public static function generateTicketNumber()
    {
        $year = date('Y');
        $lastTicket = self::where('whatsappticket_number', 'like', "TKT-{$year}-%")
            ->orderBy('whatsappticket_id', 'desc')
            ->first();

        if ($lastTicket) {
            $lastNumber = intval(substr($lastTicket->whatsappticket_number, -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf("TKT-%s-%05d", $year, $newNumber);
    }
}
