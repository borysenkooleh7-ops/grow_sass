<?php

/**
 * @fileoverview WhatsApp Message Model
 * @description Represents messages in WhatsApp tickets
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'whatsapp_messages';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsappmessage_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsappmessage_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsappmessage_updated';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'whatsappmessage_uniqueid',
        'whatsappmessage_ticketid',
        'whatsappmessage_contactid',
        'whatsappmessage_userid',
        'whatsappmessage_direction',
        'whatsappmessage_channel',
        'whatsappmessage_type',
        'whatsappmessage_content',
        'whatsappmessage_media_url',
        'whatsappmessage_media_filename',
        'whatsappmessage_media_mime',
        'whatsappmessage_media_size',
        'whatsappmessage_status',
        'whatsappmessage_external_id',
        'whatsappmessage_is_internal_note',
        'whatsappmessage_error',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'whatsappmessage_created' => 'datetime',
        'whatsappmessage_updated' => 'datetime',
        'whatsappmessage_ticketid' => 'integer',
        'whatsappmessage_contactid' => 'integer',
        'whatsappmessage_userid' => 'integer',
        'whatsappmessage_media_size' => 'integer',
        'whatsappmessage_is_internal_note' => 'boolean',
    ];

    /**
     * Relationship: Message belongs to a Ticket
     */
    public function ticket()
    {
        return $this->belongsTo('App\Models\WhatsappTicket', 'whatsappmessage_ticketid', 'whatsappticket_id');
    }

    /**
     * Relationship: Message belongs to a Contact
     */
    public function contact()
    {
        return $this->belongsTo('App\Models\WhatsappContact', 'whatsappmessage_contactid', 'whatsappcontact_id');
    }

    /**
     * Relationship: Message may be sent by a User (agent)
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'whatsappmessage_userid', 'id');
    }

    /**
     * Scope: Get incoming messages
     */
    public function scopeIncoming($query)
    {
        return $query->where('whatsappmessage_direction', 'incoming');
    }

    /**
     * Scope: Get outgoing messages
     */
    public function scopeOutgoing($query)
    {
        return $query->where('whatsappmessage_direction', 'outgoing');
    }

    /**
     * Scope: Get WhatsApp messages only
     */
    public function scopeWhatsappOnly($query)
    {
        return $query->where('whatsappmessage_channel', 'whatsapp');
    }

    /**
     * Scope: Get email messages only
     */
    public function scopeEmailOnly($query)
    {
        return $query->where('whatsappmessage_channel', 'email');
    }

    /**
     * Scope: Get non-internal messages (exclude internal notes)
     */
    public function scopeNotInternal($query)
    {
        return $query->where('whatsappmessage_is_internal_note', false);
    }

    /**
     * Scope: Get internal notes only
     */
    public function scopeInternalOnly($query)
    {
        return $query->where('whatsappmessage_is_internal_note', true);
    }

    /**
     * Check if message is incoming
     */
    public function isIncoming()
    {
        return $this->whatsappmessage_direction === 'incoming';
    }

    /**
     * Check if message is outgoing
     */
    public function isOutgoing()
    {
        return $this->whatsappmessage_direction === 'outgoing';
    }

    /**
     * Check if message is an internal note
     */
    public function isInternalNote()
    {
        return $this->whatsappmessage_is_internal_note == true;
    }

    /**
     * Check if message has media
     */
    public function hasMedia()
    {
        return !empty($this->whatsappmessage_media_url);
    }

    /**
     * Mark message as sent
     */
    public function markAsSent($externalId = null)
    {
        $this->whatsappmessage_status = 'sent';
        if ($externalId) {
            $this->whatsappmessage_external_id = $externalId;
        }
        return $this->save();
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered()
    {
        $this->whatsappmessage_status = 'delivered';
        return $this->save();
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->whatsappmessage_status = 'read';
        return $this->save();
    }

    /**
     * Mark message as failed
     */
    public function markAsFailed($error = null)
    {
        $this->whatsappmessage_status = 'failed';
        if ($error) {
            $this->whatsappmessage_error = $error;
        }
        return $this->save();
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->whatsappmessage_media_size) {
            return null;
        }

        $size = $this->whatsappmessage_media_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get sender name (user name or contact name)
     */
    public function getSenderNameAttribute()
    {
        if ($this->isIncoming() && $this->contact) {
            return $this->contact->display_name;
        } elseif ($this->isOutgoing() && $this->user) {
            return $this->user->first_name . ' ' . $this->user->last_name;
        }

        return 'Unknown';
    }

    /**
     * Get message status icon
     */
    public function getStatusIcon()
    {
        switch ($this->whatsappmessage_status) {
            case 'sent':
                return '<i class="mdi mdi-check text-muted"></i>';
            case 'delivered':
                return '<i class="mdi mdi-check-all text-info"></i>';
            case 'read':
                return '<i class="mdi mdi-check-all text-primary"></i>';
            case 'failed':
                return '<i class="mdi mdi-alert-circle text-danger"></i>';
            default:
                return '<i class="mdi mdi-clock-outline text-muted"></i>';
        }
    }
}
