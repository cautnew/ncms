<?php

namespace App\Http\Requests\Piece;

use App\Enums\PieceType;
use App\Models\Asset;
use App\Models\PageVersion;
use App\Models\Piece;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePieceRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string|Closure>
     */
    public function rules(): array
    {
        /** @var PageVersion $version */
        $version = $this->route('version');

        return [
            'type' => ['required', Rule::enum(PieceType::class)],
            'parent_piece_id' => [
                'nullable',
                'string',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($version): void {
                    if (! Piece::where('id', $value)->where('page_version_id', $version->id)->exists()) {
                        $fail('The selected parent piece does not belong to this page version.');
                    }
                },
            ],
            'slot' => ['nullable', 'string', 'max:50'],
            'region' => ['nullable', 'string', 'max:50'],
            'asset_id' => [
                'required_if:type,'.PieceType::Image->value,
                'nullable',
                'string',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($version): void {
                    if (! Asset::where('id', $value)->where('website_id', $version->page->website_id)->exists()) {
                        $fail('The selected asset does not belong to this website.');
                    }
                },
            ],
            'content' => ['nullable', 'array'],
            'content.text' => ['required_if:type,'.PieceType::Heading->value.','.PieceType::Paragraph->value, 'string'],
            'content.level' => ['required_if:type,'.PieceType::Heading->value, 'integer', 'between:1,6'],
            'content.items' => ['required_if:type,'.PieceType::ListBlock->value, 'array', 'min:1'],
            'content.items.*' => ['string'],
            'settings' => ['nullable', 'array'],
            'position' => ['nullable', 'integer', 'min:0'],
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
     * A piece's slot describes which named slot of its PARENT it occupies.
     * Root pieces (no parent) never have a slot. Nested pieces are only
     * allowed under container types (wrappers), and must use one of that
     * container's predefined slots.
     */
    private function validateParentSlotCompatibility(Validator $validator): void
    {
        $parentId = $this->input('parent_piece_id');
        $slot = $this->input('slot');

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
     * A piece's region describes which named zone of the LAYOUT (header,
     * footer, main, sidebar, ...) it renders into — only meaningful for
     * root-level pieces, and only valid when it's one of the regions the
     * version's frozen layout_snapshot actually declares.
     */
    private function validateRootRegion(Validator $validator): void
    {
        $parentId = $this->input('parent_piece_id');
        $region = $this->input('region');

        if ($region === null) {
            return;
        }

        if ($parentId !== null) {
            $validator->errors()->add('region', 'Only a root-level piece can have a region.');

            return;
        }

        /** @var PageVersion $version */
        $version = $this->route('version');
        $allowedRegions = $version->layout_snapshot['schema']['regions'] ?? [];

        if (! in_array($region, $allowedRegions, true)) {
            $validator->errors()->add('region', 'Region must be one of: '.implode(', ', $allowedRegions).'.');
        }
    }
}
