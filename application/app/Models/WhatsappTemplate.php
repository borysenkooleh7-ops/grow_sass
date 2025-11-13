<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappTemplate extends Model
{
    protected $table = 'whatsapp_templates';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsapptemplatemain_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsapptemplatemain_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsapptemplatemain_updated';

    protected $fillable = [
        'whatsapptemplatemain_uniqueid',
        'whatsapptemplatemain_title',
        'whatsapptemplatemain_category',
        'whatsapptemplatemain_message',
        'whatsapptemplatemain_language',
        'whatsapptemplatemain_buttons',
        'whatsapptemplatemain_variables',
        'whatsapptemplatemain_is_active',
        'whatsapptemplatemain_created_by'
    ];

    protected $casts = [
        'whatsapptemplatemain_buttons' => 'array',
        'whatsapptemplatemain_variables' => 'array',
        'whatsapptemplatemain_is_active' => 'boolean'
    ];

    /**
     * Get the user who created this template
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'whatsapptemplatemain_created_by', 'id');
    }

    /**
     * Scope to get active templates
     */
    public function scopeActive($query)
    {
        return $query->where('whatsapptemplatemain_is_active', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('whatsapptemplatemain_category', $category);
    }

    /**
     * Replace variables in template message
     */
    public function replaceVariables(array $values)
    {
        $message = $this->whatsapptemplatemain_message;

        $variables = $this->whatsapptemplatemain_variables;
        if ($variables && is_array($variables)) {
            foreach ($variables as $key => $variable) {
                if (isset($values[$variable])) {
                    $message = str_replace('{{' . $variable . '}}', $values[$variable], $message);
                }
            }
        }

        return $message;
    }
}
