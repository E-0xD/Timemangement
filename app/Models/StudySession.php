<?php

namespace App\Models;

use App\Enums\StudySessionType;
use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudySession extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'task_id',
        'type',
        'started_at',
        'ended_at',
        'duration_minutes',
        'notes',
    ];

    protected $casts = [
        'type'       => StudySessionType::class,
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function isActive(): bool
    {
        return is_null($this->ended_at);
    }

    public function end(): void
    {
        $this->ended_at = now();
        $this->duration_minutes = (int) $this->started_at->diffInMinutes($this->ended_at);
        $this->save();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('ended_at');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('started_at', today());
    }
}
