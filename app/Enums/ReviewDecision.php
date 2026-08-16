<?php

namespace App\Enums;

enum ReviewDecision: string
{
    case Approved = 'approved';
    case Rejected = 'rejected';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }
}
