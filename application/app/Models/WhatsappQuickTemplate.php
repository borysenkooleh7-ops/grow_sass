<?php

/**
 * @fileoverview WhatsApp Quick Template Model
 * @description Quick reply templates for faster responses
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappQuickTemplate extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'whatsapp_quick_templates';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsapptemplate_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsapptemplate_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsapptemplate_updated';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'whatsapptemplate_uniqueid',
        'whatsapptemplate_userid',
        'whatsapptemplate_name',
        'whatsapptemplate_content',
        'whatsapptemplate_shortcut',
        'whatsapptemplate_category',
    ];

    /**
     * Relationship: Template may belong to a User
     * If NULL, it's a global template
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'whatsapptemplate_userid', 'id');
    }

    /**
     * Scope: Get global templates (no user)
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('whatsapptemplate_userid');
    }

    /**
     * Scope: Get user-specific templates
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('whatsapptemplate_userid', $userId);
    }

    /**
     * Scope: Get by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('whatsapptemplate_category', $category);
    }

    /**
     * Check if template is global
     */
    public function isGlobal()
    {
        return empty($this->whatsapptemplate_userid);
    }
}
