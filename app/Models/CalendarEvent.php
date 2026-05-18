<?php

namespace App\Models;

use App\Enums\EventType;
use App\Models\Course;
use App\Models\StudyGroup;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEvent extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'study_group_id',
        'task_id',
        'title',
        'description',
        'type',
        'start_datetime',
        'end_datetime',
        'location',
        'color',
        'is_recurring',
        'recurrence_rule',
        'is_all_day',
    ];

    protected $casts = [
        'type'           => EventType::class,
        'start_datetime' => 'datetime',
        'end_datetime'   => 'datetime',
        'is_recurring'   => 'boolean',
        'is_all_day'     => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function durationMinutes(): int
    {
        return (int) $this->start_datetime->diffInMinutes($this->end_datetime);
    }

    public function scopeForDate($query, string $date)
    {
        return $query->whereDate('start_datetime', $date);
    }

    public function scopeInRange($query, string $from, string $to)
    {
        return $query->whereBetween('start_datetime', [$from, $to]);
    }
}
