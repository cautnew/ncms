<?php

namespace App\Enums;

enum WebsiteRole: string
{
    case Owner = 'owner';
    case Admin = 'admin';
    case Editor = 'editor';
    case Qa = 'qa';
    case Viewer = 'viewer';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Owner',
            self::Admin => 'Admin',
            self::Editor => 'Editor',
            self::Qa => 'QA',
            self::Viewer => 'Viewer',
        };
    }

    /**
     * Roles an admin is allowed to grant or modify on other members.
     * Owner and admin memberships can only be managed by an owner.
     *
     * @return array<int, self>
     */
    public static function assignableByAdmin(): array
    {
        return [self::Editor, self::Qa, self::Viewer];
    }
}
