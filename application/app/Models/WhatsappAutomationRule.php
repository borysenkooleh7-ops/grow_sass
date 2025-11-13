<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappAutomationRule extends Model
{
    protected $table = 'whatsapp_automation_rules';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsappautomationrule_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsappautomationrule_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsappautomationrule_updated';

    protected $fillable = [
        'whatsappautomationrule_uniqueid',
        'whatsappautomationrule_name',
        'whatsappautomationrule_description',
        'whatsappautomationrule_trigger_type',
        'whatsappautomationrule_trigger_conditions',
        'whatsappautomationrule_actions',
        'whatsappautomationrule_is_active',
        'whatsappautomationrule_stop_processing',
        'whatsappautomationrule_triggered_count',
        'whatsappautomationrule_last_triggered_at',
        'whatsappautomationrule_created_by'
    ];

    protected $casts = [
        'whatsappautomationrule_trigger_conditions' => 'array',
        'whatsappautomationrule_actions' => 'array',
        'whatsappautomationrule_is_active' => 'boolean',
        'whatsappautomationrule_stop_processing' => 'boolean',
        'whatsappautomationrule_last_triggered_at' => 'datetime'
    ];

    /**
     * Get the user who created this rule
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'whatsappautomationrule_created_by', 'id');
    }

    /**
     * Scope to get active rules
     */
    public function scopeActive($query)
    {
        return $query->where('whatsappautomationrule_is_active', true);
    }

    /**
     * Scope by trigger type
     */
    public function scopeByTrigger($query, $triggerType)
    {
        return $query->where('whatsappautomationrule_trigger_type', $triggerType);
    }

    /**
     * Increment triggered count
     */
    public function incrementTriggered()
    {
        $this->increment('whatsappautomationrule_triggered_count');
        $this->update(['whatsappautomationrule_last_triggered_at' => now()]);
    }

    /**
     * Check if conditions match
     */
    public function matchesConditions($data)
    {
        $triggerConditions = $this->whatsappautomationrule_trigger_conditions;
        if (!$triggerConditions || !is_array($triggerConditions)) {
            return true;
        }

        foreach ($triggerConditions as $condition) {
            if (!$this->evaluateCondition($condition, $data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate a single condition
     */
    private function evaluateCondition($condition, $data)
    {
        $field = $condition['field'] ?? null;
        $operator = $condition['operator'] ?? '=';
        $value = $condition['value'] ?? null;

        if (!$field || !isset($data[$field])) {
            return false;
        }

        $fieldValue = $data[$field];

        switch ($operator) {
            case '=':
            case 'equals':
                return $fieldValue == $value;
            case '!=':
            case 'not_equals':
                return $fieldValue != $value;
            case 'contains':
                return stripos($fieldValue, $value) !== false;
            case 'not_contains':
                return stripos($fieldValue, $value) === false;
            case 'starts_with':
                return stripos($fieldValue, $value) === 0;
            case 'ends_with':
                return substr($fieldValue, -strlen($value)) === $value;
            default:
                return false;
        }
    }
}
