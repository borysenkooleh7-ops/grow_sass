<?php

/**
 * @fileoverview WhatsApp Contact Model
 * @description Represents WhatsApp contacts with optional Client linking
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappContact extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'whatsapp_contacts';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsappcontact_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsappcontact_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsappcontact_updated';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'whatsappcontact_uniqueid',
        'whatsappcontact_connectionid',
        'whatsappcontact_clientid',
        'whatsappcontact_phone',
        'whatsappcontact_name',
        'whatsappcontact_company',
        'whatsappcontact_display_name',
        'whatsappcontact_profile_pic',
        'whatsappcontact_tags',
        'whatsappcontact_notes',
        'whatsappcontact_last_message_at',
        'whatsappcontact_unread_count',
        'whatsappcontact_blocked',
        'whatsappcontact_blocked_at',
        'whatsappcontact_blocked_by',
        'whatsappcontact_block_reason',
        'whatsappcontact_block_notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'whatsappcontact_last_message_at' => 'datetime',
        'whatsappcontact_blocked_at' => 'datetime',
        'whatsappcontact_unread_count' => 'integer',
        'whatsappcontact_clientid' => 'integer',
        'whatsappcontact_connectionid' => 'integer',
        'whatsappcontact_blocked' => 'boolean',
    ];

    /**
     * Relationship: Contact may be linked to a Client
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'whatsappcontact_clientid', 'client_id');
    }

    /**
     * Relationship: Contact belongs to a Connection
     */
    public function connection()
    {
        return $this->belongsTo('App\Models\WhatsappConnection', 'whatsappcontact_connectionid', 'whatsappconnection_id');
    }

    /**
     * Relationship: Contact has many Tickets
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\WhatsappTicket', 'whatsappticket_contactid', 'whatsappcontact_id')
            ->orderBy('whatsappticket_created', 'desc');
    }

    /**
     * Relationship: Contact has many Notes
     */
    public function notes()
    {
        return $this->hasMany('App\Models\WhatsappContactNote', 'whatsappcontactnote_contactid', 'whatsappcontact_id')
            ->orderBy('whatsappcontactnote_created', 'desc');
    }

    /**
     * Get display name (fallback to phone if no name)
     */
    public function getDisplayNameAttribute()
    {
        return $this->whatsappcontact_display_name ?:
               ($this->whatsappcontact_name ?: $this->whatsappcontact_phone);
    }

    /**
     * Get avatar URL (profile pic or fallback to UI Avatars)
     */
    public function getAvatarAttribute()
    {
        if ($this->whatsappcontact_profile_pic) {
            return $this->whatsappcontact_profile_pic;
        }

        // Fallback to UI Avatars
        $name = urlencode($this->display_name);
        return "https://ui-avatars.com/api/?name={$name}&size=128&background=25D366&color=fff";
    }

    /**
     * Get tags as array
     */
    public function getTagsArrayAttribute()
    {
        if (empty($this->whatsappcontact_tags)) {
            return [];
        }

        $tags = json_decode($this->whatsappcontact_tags, true);
        return is_array($tags) ? $tags : [];
    }

    /**
     * Link contact to a client
     */
    public function linkToClient($clientId)
    {
        $this->whatsappcontact_clientid = $clientId;
        return $this->save();
    }

    /**
     * Unlink contact from client
     */
    public function unlinkFromClient()
    {
        $this->whatsappcontact_clientid = null;
        return $this->save();
    }

    /**
     * Check if contact is linked to a client
     */
    public function isLinkedToClient()
    {
        return !empty($this->whatsappcontact_clientid);
    }

    /**
     * Add tags to contact
     */
    public function addTags($tagIds)
    {
        if (!is_array($tagIds)) {
            $tagIds = [$tagIds];
        }

        $currentTags = $this->tags_array;
        $newTags = array_unique(array_merge($currentTags, $tagIds));
        $this->whatsappcontact_tags = json_encode(array_values($newTags));
        return $this->save();
    }

    /**
     * Remove tags from contact
     */
    public function removeTags($tagIds)
    {
        if (!is_array($tagIds)) {
            $tagIds = [$tagIds];
        }

        $currentTags = $this->tags_array;
        $newTags = array_diff($currentTags, $tagIds);
        $this->whatsappcontact_tags = json_encode(array_values($newTags));
        return $this->save();
    }

    /**
     * Normalize phone number (remove non-digits except +)
     */
    public static function normalizePhone($phone)
    {
        // Remove all characters except digits and +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Ensure it starts with +
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Find or create contact by phone number
     */
    public static function findOrCreateByPhone($phone, $name = null, $connectionId = null)
    {
        $phone = self::normalizePhone($phone);

        $contact = self::where('whatsappcontact_phone', $phone)->first();

        if (!$contact) {
            $contact = self::create([
                'whatsappcontact_uniqueid' => str_unique(),
                'whatsappcontact_connectionid' => $connectionId,
                'whatsappcontact_phone' => $phone,
                'whatsappcontact_name' => $name ?: $phone,
                'whatsappcontact_display_name' => $name ?: $phone,
            ]);
        } elseif ($name && !$contact->whatsappcontact_name) {
            $contact->whatsappcontact_name = $name;
            $contact->whatsappcontact_display_name = $name;
            $contact->save();
        }

        return $contact;
    }

    /**
     * Block contact
     */
    public function block($reason = null, $notes = null, $userId = null)
    {
        $this->whatsappcontact_blocked = 1;
        $this->whatsappcontact_blocked_at = now();
        $this->whatsappcontact_blocked_by = $userId ?? auth()->id();
        $this->whatsappcontact_block_reason = $reason;
        $this->whatsappcontact_block_notes = $notes;
        return $this->save();
    }

    /**
     * Unblock contact
     */
    public function unblock()
    {
        $this->whatsappcontact_blocked = 0;
        $this->whatsappcontact_blocked_at = null;
        $this->whatsappcontact_blocked_by = null;
        $this->whatsappcontact_block_reason = null;
        $this->whatsappcontact_block_notes = null;
        return $this->save();
    }

    /**
     * Check if contact is blocked
     */
    public function isBlocked()
    {
        return (bool) $this->whatsappcontact_blocked;
    }

    /**
     * Relationship: User who blocked this contact
     */
    public function blocker()
    {
        return $this->belongsTo('App\Models\User', 'whatsappcontact_blocked_by', 'id');
    }

    /**
     * Scope: Get only blocked contacts
     */
    public function scopeBlocked($query)
    {
        return $query->where('whatsappcontact_blocked', 1);
    }

    /**
     * Scope: Get only non-blocked contacts
     */
    public function scopeNotBlocked($query)
    {
        return $query->where('whatsappcontact_blocked', 0);
    }
}
