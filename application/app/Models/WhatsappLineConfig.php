<?php

/**
 * @fileoverview WhatsApp Line Config Model
 * @description Configuration settings for WhatsApp connections
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLineConfig extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'whatsapp_line_configs';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsapplineconfig_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsapplineconfig_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsapplineconfig_updated';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'whatsapplineconfig_uniqueid',
        'whatsapplineconfig_connectionid',
        'whatsapplineconfig_welcome_message',
        'whatsapplineconfig_away_message',
        'whatsapplineconfig_closure_message',
        'whatsapplineconfig_inactivity_message',
        'whatsapplineconfig_inactivity_minutes',
        'whatsapplineconfig_auto_close_enabled',
        'whatsapplineconfig_auto_assign_enabled',
        'whatsapplineconfig_auto_assign_logic',
        'whatsapplineconfig_business_hours_enabled',
        'whatsapplineconfig_business_hours_start',
        'whatsapplineconfig_business_hours_end',
        'whatsapplineconfig_business_days',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'whatsapplineconfig_auto_assign_enabled' => 'boolean',
        'whatsapplineconfig_auto_close_enabled' => 'boolean',
        'whatsapplineconfig_business_hours_enabled' => 'boolean',
        'whatsapplineconfig_inactivity_minutes' => 'integer',
    ];

    /**
     * Relationship: Config belongs to a Connection
     */
    public function connection()
    {
        return $this->belongsTo('App\Models\WhatsappConnection', 'whatsapplineconfig_connectionid', 'whatsappconnection_id');
    }

    /**
     * Check if currently within business hours
     */
    public function isWithinBusinessHours()
    {
        if (!$this->whatsapplineconfig_business_hours_enabled) {
            return true; // Always available if business hours not enabled
        }

        $now = now();
        $currentDay = strtolower($now->format('l')); // monday, tuesday, etc.
        $currentTime = $now->format('H:i:s');

        // Check if today is a business day
        $businessDays = explode(',', $this->whatsapplineconfig_business_days ?? '');
        if (!in_array($currentDay, $businessDays)) {
            return false;
        }

        // Check if current time is within business hours
        if ($currentTime < $this->whatsapplineconfig_business_hours_start ||
            $currentTime > $this->whatsapplineconfig_business_hours_end) {
            return false;
        }

        return true;
    }

    /**
     * Get appropriate message (welcome or away)
     */
    public function getAppropriateMessage()
    {
        if ($this->isWithinBusinessHours()) {
            return $this->whatsapplineconfig_welcome_message;
        } else {
            return $this->whatsapplineconfig_away_message;
        }
    }
}
