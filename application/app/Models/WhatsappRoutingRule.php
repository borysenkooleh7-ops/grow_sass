<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappRoutingRule extends Model
{
    protected $table = 'whatsapp_routing_rules';

    protected $fillable = [
        'name',
        'priority',
        'conditions',
        'assign_to_type',
        'assign_to_id',
        'is_active'
    ];

    protected $casts = [
        'conditions' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the user or team assigned
     */
    public function assignedTo()
    {
        if ($this->assign_to_type === 'user') {
            return $this->belongsTo(User::class, 'assign_to_id');
        }
        // Add team relationship when Team model exists
        return null;
    }

    /**
     * Scope to get active rules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by priority
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * Check if conditions match for routing
     */
    public function matchesConditions($data)
    {
        if (!$this->conditions || !is_array($this->conditions)) {
            return true;
        }

        foreach ($this->conditions as $condition) {
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
            case 'in':
                return in_array($fieldValue, (array)$value);
            default:
                return false;
        }
    }
}
