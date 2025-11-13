<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappBroadcast extends Model
{
    protected $table = 'whatsapp_broadcasts';

    protected $primaryKey = 'whatsappbroadcast_id';

    const CREATED_AT = 'whatsappbroadcast_created';
    const UPDATED_AT = 'whatsappbroadcast_updated';

    protected $fillable = [
        'whatsappbroadcast_uniqueid',
        'whatsappbroadcast_name',
        'whatsappbroadcast_message',
        'whatsappbroadcast_recipient_type',
        'whatsappbroadcast_recipient_data',
        'whatsappbroadcast_connection_id',
        'whatsappbroadcast_template_id',
        'whatsappbroadcast_attachments',
        'whatsappbroadcast_total_recipients',
        'whatsappbroadcast_sent_count',
        'whatsappbroadcast_delivered_count',
        'whatsappbroadcast_read_count',
        'whatsappbroadcast_failed_count',
        'whatsappbroadcast_status',
        'whatsappbroadcast_scheduled_at',
        'whatsappbroadcast_started_at',
        'whatsappbroadcast_completed_at',
        'whatsappbroadcast_created_by'
    ];

    protected $casts = [
        'whatsappbroadcast_recipient_data' => 'array',
        'whatsappbroadcast_attachments' => 'array',
        'whatsappbroadcast_scheduled_at' => 'datetime',
        'whatsappbroadcast_started_at' => 'datetime',
        'whatsappbroadcast_completed_at' => 'datetime',
        'whatsappbroadcast_created' => 'datetime',
        'whatsappbroadcast_updated' => 'datetime',
    ];

    /**
     * Get the connection used for this broadcast
     */
    public function connection(): BelongsTo
    {
        return $this->belongsTo(WhatsappConnection::class, 'whatsappbroadcast_connection_id', 'whatsappconnection_id');
    }

    /**
     * Get the template used for this broadcast
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(WhatsappTemplate::class, 'whatsappbroadcast_template_id', 'whatsapptemplatemain_id');
    }

    /**
     * Get the user who created this broadcast
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'whatsappbroadcast_created_by', 'id');
    }

    /**
     * Get recipients for this broadcast
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(WhatsappBroadcastRecipient::class, 'whatsappbroadcastrecipient_broadcast_id', 'whatsappbroadcast_id');
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope draft broadcasts
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope scheduled broadcasts
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope sending broadcasts
     */
    public function scopeSending($query)
    {
        return $query->where('status', 'sending');
    }

    /**
     * Scope completed broadcasts
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get delivery rate percentage
     */
    public function getDeliveryRateAttribute()
    {
        if ($this->sent_count == 0) return 0;
        return round(($this->delivered_count / $this->sent_count) * 100, 2);
    }

    /**
     * Get read rate percentage
     */
    public function getReadRateAttribute()
    {
        if ($this->delivered_count == 0) return 0;
        return round(($this->read_count / $this->delivered_count) * 100, 2);
    }

    /**
     * Get failure rate percentage
     */
    public function getFailureRateAttribute()
    {
        if ($this->total_recipients == 0) return 0;
        return round(($this->failed_count / $this->total_recipients) * 100, 2);
    }

    /**
     * Start broadcast
     */
    public function start()
    {
        $this->update([
            'status' => 'sending',
            'started_at' => now()
        ]);
    }

    /**
     * Complete broadcast
     */
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
            'completed_at' => now()
        ]);
    }
}
