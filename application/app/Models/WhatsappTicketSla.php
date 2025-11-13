<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappTicketSla extends Model
{
    protected $table = 'whatsapp_ticket_sla';

    protected $primaryKey = 'whatsappticketsla_id';

    const CREATED_AT = 'whatsappticketsla_created';
    const UPDATED_AT = 'whatsappticketsla_updated';

    protected $fillable = [
        'whatsappticketsla_ticket_id',
        'whatsappticketsla_policy_id',
        'whatsappticketsla_type',
        'whatsappticketsla_target_time',
        'whatsappticketsla_achieved_at',
        'whatsappticketsla_status'
    ];

    protected $casts = [
        'whatsappticketsla_target_time' => 'datetime',
        'whatsappticketsla_achieved_at' => 'datetime'
    ];

    /**
     * Get the ticket
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(WhatsappTicket::class, 'whatsappticketsla_ticket_id', 'whatsappticket_id');
    }

    /**
     * Get the SLA policy
     */
    public function slaPolicy(): BelongsTo
    {
        return $this->belongsTo(WhatsappSlaPolicy::class, 'whatsappticketsla_policy_id', 'whatsappslapolis_id');
    }

    /**
     * Scope to get breached SLAs
     */
    public function scopeBreached($query)
    {
        return $query->where('whatsappticketsla_status', 'breached');
    }

    /**
     * Scope to get at-risk SLAs
     */
    public function scopeAtRisk($query)
    {
        return $query->where('whatsappticketsla_status', 'at_risk');
    }

    /**
     * Scope to get met SLAs
     */
    public function scopeMet($query)
    {
        return $query->where('whatsappticketsla_status', 'met');
    }

    /**
     * Scope to get pending SLAs
     */
    public function scopePending($query)
    {
        return $query->where('whatsappticketsla_status', 'pending');
    }

    /**
     * Check if SLA is breached
     */
    public function isBreached()
    {
        return $this->whatsappticketsla_status === 'breached' ||
               (!$this->whatsappticketsla_achieved_at && $this->whatsappticketsla_target_time && now()->greaterThan($this->whatsappticketsla_target_time));
    }

    /**
     * Check if SLA is at risk (within 20% of target time)
     */
    public function isAtRisk()
    {
        if ($this->whatsappticketsla_achieved_at || !$this->whatsappticketsla_target_time) {
            return false;
        }

        $timeRemaining = now()->diffInMinutes($this->whatsappticketsla_target_time, false);
        $totalTime = $this->whatsappticketsla_created->diffInMinutes($this->whatsappticketsla_target_time);

        return $timeRemaining > 0 && ($timeRemaining / $totalTime) <= 0.2;
    }

    /**
     * Mark SLA as achieved
     */
    public function markAsAchieved()
    {
        $this->update([
            'whatsappticketsla_achieved_at' => now(),
            'whatsappticketsla_status' => 'met'
        ]);
    }

    /**
     * Update SLA status
     */
    public function updateStatus()
    {
        if ($this->isBreached()) {
            $this->update(['whatsappticketsla_status' => 'breached']);
        } elseif ($this->isAtRisk()) {
            $this->update(['whatsappticketsla_status' => 'at_risk']);
        }
    }
}
