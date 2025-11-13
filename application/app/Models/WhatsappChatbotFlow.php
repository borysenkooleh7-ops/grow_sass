<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappChatbotFlow extends Model
{
    protected $table = 'whatsapp_chatbot_flows';

    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'trigger_value',
        'is_active',
        'triggered_count'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the steps for this flow
     */
    public function steps(): HasMany
    {
        return $this->hasMany(WhatsappChatbotStep::class, 'flow_id')->orderBy('step_order');
    }

    /**
     * Get sessions for this flow
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(WhatsappChatbotSession::class, 'flow_id');
    }

    /**
     * Scope to get active flows
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by trigger type
     */
    public function scopeByTrigger($query, $triggerType)
    {
        return $query->where('trigger_type', $triggerType);
    }

    /**
     * Increment triggered count
     */
    public function incrementTriggered()
    {
        $this->increment('triggered_count');
    }

    /**
     * Get first step
     */
    public function getFirstStep()
    {
        return $this->steps()->orderBy('step_order')->first();
    }

    /**
     * Check if trigger matches
     */
    public function matchesTrigger($message, $context = [])
    {
        switch ($this->trigger_type) {
            case 'keyword':
                return stripos($message, $this->trigger_value) !== false;

            case 'greeting':
                $greetings = ['hi', 'hello', 'hey', 'start', 'menu'];
                foreach ($greetings as $greeting) {
                    if (stripos($message, $greeting) !== false) {
                        return true;
                    }
                }
                return false;

            case 'outside_hours':
                // Check if current time is outside business hours
                return isset($context['outside_hours']) && $context['outside_hours'];

            case 'unassigned':
                return isset($context['unassigned']) && $context['unassigned'];

            default:
                return false;
        }
    }
}
