<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappContactNote extends Model
{
    protected $table = 'whatsapp_contact_notes';

    protected $fillable = [
        'contact_id',
        'note',
        'created_by'
    ];

    /**
     * Get the contact this note belongs to
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(WhatsappContact::class, 'contact_id');
    }

    /**
     * Get the user who created this note
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get notes for a specific contact
     */
    public function scopeForContact($query, $contactId)
    {
        return $query->where('contact_id', $contactId);
    }

    /**
     * Scope to get notes created by a specific user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}
