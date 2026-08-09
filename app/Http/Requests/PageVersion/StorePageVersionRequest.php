<?php

namespace App\Http\Requests\PageVersion;

use App\Models\Layout;
use App\Models\Page;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePageVersionRequest extends FormRequest
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
        /** @var Page $page */
        $page = $this->route('page');

        return [
            'layout_id' => [
                'required',
                'string',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($page): void {
                    if (! Layout::where('id', $value)->where('website_id', $page->website_id)->exists()) {
                        $fail("The selected layout does not belong to this page's website.");
                    }
                },
            ],
            'seo' => ['nullable', 'array'],
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
