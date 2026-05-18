<?php

namespace App\Models;

use App\Enums\GroupRole;
use App\Models\StudyGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyGroupMember extends Model
{
    protected $fillable = [
        'study_group_id',
        'user_id',
        'role',
        'joined_at',
    ];

    protected $casts = [
        'role'      => GroupRole::class,
        'joined_at' => 'datetime',
    ];

    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
