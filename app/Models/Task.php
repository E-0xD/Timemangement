<?php

namespace App\Models;

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\CalendarEvent;
use App\Models\Course;
use App\Models\File;
use App\Models\StudySession;
use App\Models\Subtask;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'due_date',
        'due_time',
        'is_recurring',
        'recurrence_rule',
        'completed_at',
    ];

    protected $casts = [
        'category'     => TaskCategory::class,
        'priority'     => TaskPriority::class,
        'status'       => TaskStatus::class,
        'due_date'     => 'date',
        'is_recurring' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('sort_order');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function studySessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== TaskStatus::Completed
            && $this->status !== TaskStatus::Cancelled;
    }

    public function completionPercentage(): int
    {
        $total = $this->subtasks->count();
        if ($total === 0) {
            return $this->status === TaskStatus::Completed ? 100 : 0;
        }

        return (int) round(($this->subtasks->where('is_completed', true)->count() / $total) * 100);
    }

    public function scopePending($query)
    {
        return $query->where('status', TaskStatus::Pending->value);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', TaskStatus::InProgress->value);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', TaskStatus::Completed->value);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
            ->whereNotIn('status', [TaskStatus::Completed->value, TaskStatus::Cancelled->value]);
    }
}
