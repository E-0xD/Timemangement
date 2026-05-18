<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'day_of_week',
        'start_time',
        'end_time',
        'location',
        'lecturer',
        'color',
        'is_recurring',
    ];

    protected $casts = [
        'day_of_week'  => DayOfWeek::class,
        'is_recurring' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function conflictsWith(self $other): bool
    {
        if ($this->day_of_week !== $other->day_of_week) {
            return false;
        }

        return $this->start_time < $other->end_time
            && $this->end_time > $other->start_time;
    }
}
