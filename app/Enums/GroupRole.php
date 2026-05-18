<?php

namespace App\Enums;

enum GroupRole: string
{
    case Owner  = 'owner';
    case Admin  = 'admin';
    case Member = 'member';

    public function label(): string
    {
        return match ($this) {
            self::Owner  => 'Owner',
            self::Admin  => 'Admin',
            self::Member => 'Member',
        };
    }

    public function canManageMembers(): bool
    {
        return in_array($this, [self::Owner, self::Admin]);
    }

    public function canDeleteGroup(): bool
    {
        return $this === self::Owner;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
