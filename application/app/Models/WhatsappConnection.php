<?php

/**
 * @fileoverview WhatsApp Connection Model
 * @description Represents a WhatsApp Business API connection/line
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappConnection extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'whatsapp_connections';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsappconnection_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsappconnection_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsappconnection_updated';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'whatsappconnection_uniqueid',
        'whatsappconnection_creatorid',
        'whatsappconnection_name',
        'whatsappconnection_phone',
        'whatsappconnection_type',
        'whatsappconnection_status',
        'whatsappconnection_is_active',
        'whatsappconnection_qr_code',
        'whatsappconnection_webhook_url',
        'whatsappconnection_webhook_secret',
        'whatsappconnection_api_key',
        'whatsappconnection_api_secret',
        'whatsappconnection_phone_number_id',
        'whatsappconnection_business_id',
        'whatsappconnection_instance_id',
        'whatsappconnection_from_number',
        'whatsappconnection_last_connected',
        'whatsappconnection_last_error',
        'whatsappconnection_error_message',
        'whatsappconnection_settings',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'whatsappconnection_last_connected' => 'datetime',
        'whatsappconnection_created' => 'datetime',
        'whatsappconnection_updated' => 'datetime',
        'whatsappconnection_creatorid' => 'integer',
    ];

    /**
     * Relationship: Connection has one Line Config
     */
    public function lineConfig()
    {
        return $this->hasOne('App\Models\WhatsappLineConfig', 'whatsapplineconfig_connectionid', 'whatsappconnection_id');
    }

    /**
     * Relationship: Connection has many Contacts
     */
    public function contacts()
    {
        return $this->hasMany('App\Models\WhatsappContact', 'whatsappcontact_connectionid', 'whatsappconnection_id');
    }

    /**
     * Relationship: Connection has many Tickets
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\WhatsappTicket', 'whatsappticket_connectionid', 'whatsappconnection_id');
    }

    /**
     * Relationship: Created by User
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'whatsappconnection_creatorid', 'id');
    }

    /**
     * Check if connection is connected
     */
    public function isConnected()
    {
        return $this->whatsappconnection_status === 'connected';
    }

    /**
     * Check if connection is disconnected
     */
    public function isDisconnected()
    {
        return $this->whatsappconnection_status === 'disconnected';
    }

    /**
     * Check if connection is pending
     */
    public function isPending()
    {
        return $this->whatsappconnection_status === 'pending';
    }

    /**
     * Check if connection has error
     */
    public function hasError()
    {
        return $this->whatsappconnection_status === 'error';
    }

    /**
     * Scope to get only connected connections
     */
    public function scopeConnected($query)
    {
        return $query->where('whatsappconnection_status', 'connected');
    }

    /**
     * Scope to get only disconnected connections
     */
    public function scopeDisconnected($query)
    {
        return $query->where('whatsappconnection_status', 'disconnected');
    }

    /**
     * Scope to get only pending connections
     */
    public function scopePending($query)
    {
        return $query->where('whatsappconnection_status', 'pending');
    }

    /**
     * Mark connection as connected
     */
    public function markAsConnected()
    {
        $this->whatsappconnection_status = 'connected';
        $this->whatsappconnection_last_connected = now();
        return $this->save();
    }

    /**
     * Mark connection as disconnected
     */
    public function markAsDisconnected()
    {
        $this->whatsappconnection_status = 'disconnected';
        return $this->save();
    }

    /**
     * Mark connection as pending
     */
    public function markAsPending()
    {
        $this->whatsappconnection_status = 'pending';
        return $this->save();
    }

    /**
     * Mark connection as error
     */
    public function markAsError()
    {
        $this->whatsappconnection_status = 'error';
        return $this->save();
    }

    /**
     * Get settings as array
     */
    public function getSettingsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        $settings = json_decode($value, true);
        return is_array($settings) ? $settings : [];
    }

    /**
     * Set settings from array
     */
    public function setSettingsAttribute($value)
    {
        $this->attributes['whatsappconnection_settings'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get default WATI connection (static method)
     * Used for single-connection mode
     *
     * @return WhatsappConnection|null
     */
    public static function getDefaultConnection()
    {
        return self::where('whatsappconnection_uniqueid', 'default-wati-connection')
            ->where('whatsappconnection_is_active', 1)
            ->first();
    }

    /**
     * Get WATI token from environment
     *
     * @return string
     */
    public static function getWatiToken()
    {
        return config('whatsapp.wati.access_token');
    }

    /**
     * Check if WATI is connected
     *
     * @return bool
     */
    public static function isWatiConnected()
    {
        $connection = self::getDefaultConnection();
        return $connection && $connection->whatsappconnection_status === 'connected';
    }

    /**
     * Mark default connection as connected
     * Called after successful QR scan
     *
     * @param string|null $phone
     * @return bool
     */
    public static function markDefaultAsConnected($phone = null)
    {
        $connection = self::getDefaultConnection();

        if ($connection) {
            $connection->whatsappconnection_status = 'connected';
            $connection->whatsappconnection_last_connected = now();

            if ($phone) {
                $connection->whatsappconnection_phone = $phone;
            }

            return $connection->save();
        }

        return false;
    }

    /**
     * Update QR code data
     *
     * @param string $qrCode
     * @return bool
     */
    public static function updateQRCode($qrCode)
    {
        $connection = self::getDefaultConnection();

        if ($connection) {
            $connection->whatsappconnection_qr_code = $qrCode;
            $connection->whatsappconnection_updated = now();
            return $connection->save();
        }

        return false;
    }
}

