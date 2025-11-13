<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappSlaPolicy extends Model
{
    protected $table = 'whatsapp_sla_policies';

    protected $primaryKey = 'whatsappslapolis_id';

    const CREATED_AT = 'whatsappslapolis_created';
    const UPDATED_AT = 'whatsappslapolis_updated';

    protected $fillable = [
        'whatsappslapolis_name',
        'whatsappslapolis_priority',
        'whatsappslapolis_first_response_minutes',
        'whatsappslapolis_resolution_minutes',
        'whatsappslapolis_business_hours_only',
        'whatsappslapolis_is_active'
    ];

    protected $casts = [
        'whatsappslapolis_business_hours_only' => 'boolean',
        'whatsappslapolis_is_active' => 'boolean'
    ];

    /**
     * Get SLA tracking records for this policy
     */
    public function slaTracking(): HasMany
    {
        return $this->hasMany(WhatsappTicketSla::class, 'whatsappticketsla_policy_id', 'whatsappslapolis_id');
    }

    /**
     * Scope to get active policies
     */
    public function scopeActive($query)
    {
        return $query->where('whatsappslapolis_is_active', true);
    }

    /**
     * Scope by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('whatsappslapolis_priority', $priority);
    }

    /**
     * Get first response time in minutes
     */
    public function getFirstResponseTimeInMinutes()
    {
        return $this->whatsappslapolis_first_response_minutes;
    }

    /**
     * Get resolution time in minutes
     */
    public function getResolutionTimeInMinutes()
    {
        return $this->whatsappslapolis_resolution_minutes;
    }

    /**
     * Calculate target time for first response
     */
    public function calculateFirstResponseTarget($startTime = null)
    {
        $start = $startTime ?? now();
        return $start->addMinutes($this->whatsappslapolis_first_response_minutes);
    }

    /**
     * Calculate target time for resolution
     */
    public function calculateResolutionTarget($startTime = null)
    {
        $start = $startTime ?? now();
        return $start->addMinutes($this->whatsappslapolis_resolution_minutes);
    }
}
