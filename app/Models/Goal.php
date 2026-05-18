<?php

namespace App\Models;

use App\Enums\GoalCategory;
use App\Enums\GoalPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'period',
        'target_value',
        'current_value',
        'target_date',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'category'     => GoalCategory::class,
        'period'       => GoalPeriod::class,
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'target_date'  => 'date',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function progressPercentage(): int
    {
        if ($this->target_value <= 0) {
            return 0;
        }

        return min(100, (int) round(($this->current_value / $this->target_value) * 100));
    }

    public function isExpired(): bool
    {
        return $this->target_date
            && $this->target_date->isPast()
            && ! $this->is_completed;
    }

    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeForPeriod($query, GoalPeriod $period)
    {
        return $query->where('period', $period->value);
    }
}
