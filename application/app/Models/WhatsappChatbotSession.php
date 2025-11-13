<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappChatbotSession extends Model
{
    protected $table = 'whatsapp_chatbot_sessions';

    protected $fillable = [
        'ticket_id',
        'flow_id',
        'current_step_id',
        'context',
        'status',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'context' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the ticket this session belongs to
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(WhatsappTicket::class, 'ticket_id');
    }

    /**
     * Get the chatbot flow
     */
    public function flow(): BelongsTo
    {
        return $this->belongsTo(WhatsappChatbotFlow::class, 'flow_id');
    }

    /**
     * Get the current step
     */
    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(WhatsappChatbotStep::class, 'current_step_id');
    }

    /**
     * Scope active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope completed sessions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope abandoned sessions
     */
    public function scopeAbandoned($query)
    {
        return $query->where('status', 'abandoned');
    }

    /**
     * Move to next step
     */
    public function moveToStep($stepId)
    {
        $this->update(['current_step_id' => $stepId]);
    }

    /**
     * Complete the session
     */
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    /**
     * Abandon the session
     */
    public function abandon()
    {
        $this->update([
            'status' => 'abandoned',
            'completed_at' => now()
        ]);
    }

    /**
     * Hand off to agent
     */
    public function handOff()
    {
        $this->update([
            'status' => 'handed_off',
            'completed_at' => now()
        ]);
    }

    /**
     * Store context variable
     */
    public function setContext($key, $value)
    {
        $context = $this->context ?? [];
        $context[$key] = $value;
        $this->update(['context' => $context]);
    }

    /**
     * Get context variable
     */
    public function getContext($key, $default = null)
    {
        return $this->context[$key] ?? $default;
    }

    /**
     * Check if session is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
