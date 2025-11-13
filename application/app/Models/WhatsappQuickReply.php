<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappQuickReply extends Model
{
    protected $table = 'whatsapp_quick_replies';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsappquickreply_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsappquickreply_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsappquickreply_updated';

    protected $fillable = [
        'whatsappquickreply_uniqueid',
        'whatsappquickreply_title',
        'whatsappquickreply_shortcut',
        'whatsappquickreply_message',
        'whatsappquickreply_category',
        'whatsappquickreply_is_shared',
        'whatsappquickreply_created_by'
    ];

    protected $casts = [
        'whatsappquickreply_is_shared' => 'boolean'
    ];

    /**
     * Get the user who created this quick reply
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'whatsappquickreply_created_by', 'id');
    }

    /**
     * Scope to get shared quick replies
     */
    public function scopeShared($query)
    {
        return $query->where('whatsappquickreply_is_shared', true);
    }

    /**
     * Scope to get user's own quick replies
     */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('whatsappquickreply_created_by', $userId);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('whatsappquickreply_category', $category);
    }

    /**
     * Search by shortcut
     */
    public function scopeByShortcut($query, $shortcut)
    {
        return $query->where('whatsappquickreply_shortcut', $shortcut);
    }

    /**
     * Attribute accessors for cleaner view syntax
     */
    public function getTitleAttribute($value)
    {
        return $this->attributes['whatsappquickreply_title'] ?? $value;
    }

    public function getShortcutAttribute($value)
    {
        return $this->attributes['whatsappquickreply_shortcut'] ?? $value;
    }

    public function getMessageAttribute($value)
    {
        return $this->attributes['whatsappquickreply_message'] ?? $value;
    }

    public function getCategoryAttribute($value)
    {
        return $this->attributes['whatsappquickreply_category'] ?? $value;
    }

    public function getIsSharedAttribute($value)
    {
        return $this->attributes['whatsappquickreply_is_shared'] ?? $value;
    }

    public function getIdAttribute($value)
    {
        return $this->attributes['whatsappquickreply_id'] ?? $value;
    }
}
