<?php

namespace App\Models;

use App\Enums\NotificationChannel;
use App\Enums\NotificationType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'type',
        'channel',
        'is_read',
        'read_at',
        'scheduled_at',
        'sent_at',
        'data',
    ];

    protected $casts = [
        'type'         => NotificationType::class,
        'channel'      => NotificationChannel::class,
        'is_read'      => 'boolean',
        'read_at'      => 'datetime',
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
        'data'         => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
