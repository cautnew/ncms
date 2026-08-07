<?php

namespace App\Http\Requests\PageVersion;

use App\Models\Layout;
use App\Models\PageVersion;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePageVersionRequest extends FormRequest
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
     * Only the seo snapshot and the layout (which re-derives layout_snapshot)
     * can be updated here — content itself is managed through the Pieces API.
     *
     * @return array<string, ValidationRule|array<mixed>|string|Closure>
     */
    public function rules(): array
    {
        /** @var PageVersion $version */
        $version = $this->route('version');

        return [
            'layout_id' => [
                'sometimes',
                'string',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($version): void {
                    if (! Layout::where('id', $value)->where('website_id', $version->page->website_id)->exists()) {
                        $fail("The selected layout does not belong to this page version's website.");
                    }
                },
            ],
            'seo' => ['sometimes', 'nullable', 'array'],
            'seo.title' => ['nullable', 'string', 'max:255'],
            'seo.description' => ['nullable', 'string', 'max:500'],
            'seo.keywords' => ['nullable', 'array'],
            'seo.keywords.*' => ['string', 'max:100'],
            'seo.canonical_url' => ['nullable', 'url', 'max:2048'],
            'seo.og_title' => ['nullable', 'string', 'max:255'],
            'seo.og_description' => ['nullable', 'string', 'max:500'],
            'seo.og_image' => ['nullable', 'string', 'max:2048'],
            'seo.no_index' => ['nullable', 'boolean'],
        ];
    }
}
