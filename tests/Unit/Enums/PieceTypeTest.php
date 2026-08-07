<?php

use App\Enums\PieceType;

it('labels every type', function (PieceType $type, string $label) {
    expect($type->label())->toBe($label);
})->with([
    'heading' => [PieceType::Heading, 'Heading'],
    'paragraph' => [PieceType::Paragraph, 'Paragraph'],
    'image' => [PieceType::Image, 'Image'],
    'list' => [PieceType::ListBlock, 'List'],
    'two_columns_wrapper' => [PieceType::TwoColumnsWrapper, 'Two Columns Wrapper'],
    'text_image_wrapper' => [PieceType::TextImageWrapper, 'Text + Image Wrapper'],
]);

it('only wrapper types are containers', function (PieceType $type, bool $expected) {
    expect($type->isContainer())->toBe($expected);
})->with([
    'heading' => [PieceType::Heading, false],
    'paragraph' => [PieceType::Paragraph, false],
    'image' => [PieceType::Image, false],
    'list' => [PieceType::ListBlock, false],
    'two_columns_wrapper' => [PieceType::TwoColumnsWrapper, true],
    'text_image_wrapper' => [PieceType::TextImageWrapper, true],
]);

it('exposes the correct allowed child slots per container type, and null for leaves', function () {
    expect(PieceType::TwoColumnsWrapper->allowedChildSlots())->toBe(['left', 'right']);
    expect(PieceType::TextImageWrapper->allowedChildSlots())->toBe(['text', 'image']);
    expect(PieceType::Heading->allowedChildSlots())->toBeNull();
    expect(PieceType::Paragraph->allowedChildSlots())->toBeNull();
    expect(PieceType::Image->allowedChildSlots())->toBeNull();
    expect(PieceType::ListBlock->allowedChildSlots())->toBeNull();
});

it('is backed by the expected string values', function () {
    expect(PieceType::Heading->value)->toBe('heading');
    expect(PieceType::Paragraph->value)->toBe('paragraph');
    expect(PieceType::Image->value)->toBe('image');
    expect(PieceType::ListBlock->value)->toBe('list');
    expect(PieceType::TwoColumnsWrapper->value)->toBe('two_columns_wrapper');
    expect(PieceType::TextImageWrapper->value)->toBe('text_image_wrapper');
});
