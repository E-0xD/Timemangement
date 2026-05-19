<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Models\Achievement;
use App\Models\AppNotification;
use App\Models\CalendarEvent;
use App\Models\Course;
use App\Models\Department;
use App\Models\File;
use App\Models\Goal;
use App\Models\Message;
use App\Models\Note;
use App\Models\Semester;
use App\Models\StudyGroup;
use App\Models\StudyGroupMember;
use App\Models\StudySession;
use App\Models\Task;
use App\Models\Timetable;
use App\Models\UserAchievement;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'phone',
        'school',
        'department_id',
        'telegram_chat_id',
        'dark_mode',
        'xp_points',
        'study_streak',
        'last_study_date',
        'notification_preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'        => 'datetime',
            'password'                 => 'hashed',
            'role'                     => UserRole::class,
            'dark_mode'                => 'boolean',
            'last_study_date'          => 'date',
            'notification_preferences' => 'array',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function ownedDepartments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function ownedSemesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function studySessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function ownedStudyGroups(): HasMany
    {
        return $this->hasMany(StudyGroup::class, 'owner_id');
    }

    public function studyGroups(): BelongsToMany
    {
        return $this->belongsToMany(StudyGroup::class, 'study_group_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function studyGroupMemberships(): HasMany
    {
        return $this->hasMany(StudyGroupMember::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isStudent(): bool
    {
        return $this->role === UserRole::Student;
    }

    public function avatarUrl(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->avatar);
    }

    public function getNotificationPreference(string $type): bool
    {
        return (bool) (($this->notification_preferences ?? [])[$type] ?? true);
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function todayStudyMinutes(): int
    {
        return (int) $this->studySessions()
            ->whereDate('started_at', today())
            ->whereNotNull('ended_at')
            ->sum('duration_minutes');
    }

    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()->where('is_read', false)->count();
    }
}

