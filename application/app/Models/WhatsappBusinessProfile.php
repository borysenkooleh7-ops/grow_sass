<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappBusinessProfile extends Model
{
    protected $table = 'whatsapp_business_profile';

    protected $fillable = [
        'tenant_id',
        'business_name',
        'about',
        'category',
        'email',
        'website',
        'profile_picture',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'business_hours'
    ];

    protected $casts = [
        'business_hours' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Get business hours for a specific day
     */
    public function getBusinessHoursForDay($day)
    {
        if (!$this->business_hours || !is_array($this->business_hours)) {
            return null;
        }

        return $this->business_hours[$day] ?? null;
    }

    /**
     * Check if currently within business hours
     */
    public function isWithinBusinessHours($timezone = null)
    {
        if (!$this->business_hours || !is_array($this->business_hours)) {
            return true; // If no business hours set, assume always open
        }

        $now = now($timezone ?? config('app.timezone'));
        $dayOfWeek = strtolower($now->format('l')); // monday, tuesday, etc.
        $currentTime = $now->format('H:i');

        $dayHours = $this->getBusinessHoursForDay($dayOfWeek);

        if (!$dayHours || !isset($dayHours['open']) || !isset($dayHours['close'])) {
            return false; // Day not configured or closed
        }

        if ($dayHours['is_closed'] ?? false) {
            return false;
        }

        return $currentTime >= $dayHours['open'] && $currentTime <= $dayHours['close'];
    }

    /**
     * Get formatted address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Check if profile has coordinates
     */
    public function hasCoordinates()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }
}
