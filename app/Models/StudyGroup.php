<?php

namespace App\Models;

use App\Enums\GroupRole;
use App\Models\CalendarEvent;
use App\Models\Message;
use App\Models\StudyGroupMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class StudyGroup extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'is_public',
        'invite_code',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $group) {
            if (empty($group->invite_code)) {
                $group->invite_code = strtoupper(Str::random(8));
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'study_group_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function memberRecords(): HasMany
    {
        return $this->hasMany(StudyGroupMember::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function hasMember(int $userId): bool
    {
        return $this->members()->where('users.id', $userId)->exists();
    }

    public function getMemberRole(int $userId): ?GroupRole
    {
        $member = $this->memberRecords()->where('user_id', $userId)->first();

        // $member->role is already cast to GroupRole by StudyGroupMember::$casts
        return $member?->role instanceof GroupRole ? $member->role : null;
    }
}
