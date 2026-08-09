<?php

namespace App\Http\Requests\Piece;

use App\Models\Asset;
use App\Models\Piece;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePieceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Authorization is handled by the #[Authorize] attribute on the controller action.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * The piece's type is immutable after creation (its content/settings shape
     * depends on it) — only its placement and payload can be updated here.
     *
     * @return array<string, ValidationRule|array<mixed>|string|Closure>
     */
    public function rules(): array
    {
        /** @var Piece $piece */
        $piece = $this->route('piece');

        return [
            'parent_piece_id' => [
                'sometimes',
                'nullable',
                'string',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($piece): void {
                    if (! Piece::where('id', $value)->where('page_version_id', $piece->page_version_id)->exists()) {
                        $fail('The selected parent piece does not belong to this page version.');

                        return;
                    }

                    if ($this->wouldCreateCycle($piece, $value)) {
                        $fail('A piece cannot be moved under one of its own descendants.');
                    }
                },
            ],
            'slot' => ['sometimes', 'nullable', 'string', 'max:50'],
            'region' => ['sometimes', 'nullable', 'string', 'max:50'],
            'asset_id' => [
                'sometimes',
                'nullable',
                'string',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($piece): void {
                    if (! Asset::where('id', $value)->where('website_id', $piece->pageVersion->page->website_id)->exists()) {
                        $fail('The selected asset does not belong to this website.');
                    }
                },
            ],
            'content' => ['sometimes', 'nullable', 'array'],
            'settings' => ['sometimes', 'nullable', 'array'],
            'position' => ['sometimes', 'nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @param  Validator  $validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->validateParentSlotCompatibility($validator);
            $this->validateRootRegion($validator);
        });
    }

    /**
     * Same rule as on creation, applied against the *effective* parent/slot
     * (whichever of the two was actually provided in this request, falling
     * back to the piece's current value otherwise).
     */
    private function validateParentSlotCompatibility(Validator $validator): void
    {
        /** @var Piece $piece */
        $piece = $this->route('piece');

        $parentId = $this->has('parent_piece_id') ? $this->input('parent_piece_id') : $piece->parent_piece_id;
        $slot = $this->has('slot') ? $this->input('slot') : $piece->slot;

        if ($parentId === null) {
            if ($slot !== null) {
                $validator->errors()->add('slot', 'A root-level piece cannot have a slot.');
            }

            return;
        }

        $parent = Piece::find($parentId);

        if ($parent === null) {
            return;
        }

        if (! $parent->type->isContainer()) {
            $validator->errors()->add('parent_piece_id', 'The selected parent piece cannot contain children.');

            return;
        }

        $allowedSlots = $parent->type->allowedChildSlots() ?? [];

        if ($slot === null || ! in_array($slot, $allowedSlots, true)) {
            $validator->errors()->add('slot', 'Slot must be one of: '.implode(', ', $allowedSlots).'.');
        }
    }

    /**
     * Same rule as on creation, applied against the *effective* parent/region.
     */
    private function validateRootRegion(Validator $validator): void
    {
        /** @var Piece $piece */
        $piece = $this->route('piece');

        $parentId = $this->has('parent_piece_id') ? $this->input('parent_piece_id') : $piece->parent_piece_id;
        $region = $this->has('region') ? $this->input('region') : $piece->region;

        if ($region === null) {
            return;
        }

        if ($parentId !== null) {
            $validator->errors()->add('region', 'Only a root-level piece can have a region.');

            return;
        }

        $allowedRegions = $piece->pageVersion->layout_snapshot['schema']['regions'] ?? [];

        if (! in_array($region, $allowedRegions, true)) {
            $validator->errors()->add('region', 'Region must be one of: '.implode(', ', $allowedRegions).'.');
        }
    }

    /**
     * Whether reparenting $piece under $candidateParentId would make the
     * piece an ancestor of itself.
     */
    private function wouldCreateCycle(Piece $piece, string $candidateParentId): bool
    {
        if ($candidateParentId === $piece->id) {
            return true;
        }

        $current = Piece::find($candidateParentId);

        while ($current !== null) {
            if ($current->id === $piece->id) {
                return true;
            }

            $current = $current->parent_piece_id === null ? null : Piece::find($current->parent_piece_id);
        }

        return false;
    }
}
