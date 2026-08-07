<?php

namespace App\Enums;

enum PieceType: string
{
    case Heading = 'heading';
    case Paragraph = 'paragraph';
    case Image = 'image';
    case ListBlock = 'list';
    case TwoColumnsWrapper = 'two_columns_wrapper';
    case TextImageWrapper = 'text_image_wrapper';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Heading => 'Heading',
            self::Paragraph => 'Paragraph',
            self::Image => 'Image',
            self::ListBlock => 'List',
            self::TwoColumnsWrapper => 'Two Columns Wrapper',
            self::TextImageWrapper => 'Text + Image Wrapper',
        };
    }

    /**
     * Wrapper types are the only ones allowed to hold children, and only in
     * their predefined slots — this is what makes the piece tree infinitely
     * nestable (a wrapper's slot can itself hold another wrapper) while still
     * keeping leaf types (heading, paragraph, image, list) childless.
     */
    public function isContainer(): bool
    {
        return in_array($this, [self::TwoColumnsWrapper, self::TextImageWrapper], true);
    }

    /**
     * The slot names a child of this piece type must be assigned to.
     * Null for leaf types, which cannot have children at all.
     *
     * @return array<int, string>|null
     */
    public function allowedChildSlots(): ?array
    {
        return match ($this) {
            self::TwoColumnsWrapper => ['left', 'right'],
            self::TextImageWrapper => ['text', 'image'],
            default => null,
        };
    }
}
