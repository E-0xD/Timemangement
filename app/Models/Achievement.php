<?php

namespace App\Models;

use App\Enums\AchievementType;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'badge_color',
        'xp_value',
        'type',
        'threshold',
    ];

    protected $casts = [
        'type'      => AchievementType::class,
        'xp_value'  => 'integer',
        'threshold' => 'integer',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }
}
