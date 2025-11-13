<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappChatbotStep extends Model
{
    protected $table = 'whatsapp_chatbot_steps';

    protected $fillable = [
        'flow_id',
        'step_order',
        'type',
        'content',
        'options',
        'next_step_id'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Get the flow this step belongs to
     */
    public function flow(): BelongsTo
    {
        return $this->belongsTo(WhatsappChatbotFlow::class, 'flow_id');
    }

    /**
     * Get the next step
     */
    public function nextStep(): BelongsTo
    {
        return $this->belongsTo(WhatsappChatbotStep::class, 'next_step_id');
    }

    /**
     * Get previous steps that point to this step
     */
    public function previousSteps()
    {
        return $this->hasMany(WhatsappChatbotStep::class, 'next_step_id');
    }

    /**
     * Scope steps for a specific flow
     */
    public function scopeForFlow($query, $flowId)
    {
        return $query->where('flow_id', $flowId);
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if step is a question
     */
    public function isQuestion()
    {
        return $this->type === 'question';
    }

    /**
     * Check if step is a message
     */
    public function isMessage()
    {
        return $this->type === 'message';
    }

    /**
     * Check if step is a condition
     */
    public function isCondition()
    {
        return $this->type === 'condition';
    }

    /**
     * Check if step is an action
     */
    public function isAction()
    {
        return $this->type === 'action';
    }

    /**
     * Process user response for this step
     */
    public function processResponse($response, $session)
    {
        if ($this->isQuestion() && $this->options) {
            // Find matching option
            foreach ($this->options as $option) {
                if (isset($option['value']) && strtolower($response) === strtolower($option['value'])) {
                    return $option['next_step_id'] ?? $this->next_step_id;
                }
            }
        }

        return $this->next_step_id;
    }
}
