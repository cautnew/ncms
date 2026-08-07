<?php

namespace App\Http\Requests\Page;

use App\Models\Layout;
use App\Models\Page;
use App\Models\Website;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
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
        /** @var Website $website */
        $website = $this->route('website');
        /** @var Page $page */
        $page = $this->route('page');

        return [
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')
                    ->where(fn ($query) => $query->where('website_id', $website->id))
                    ->ignore($page->id)
                    ->withoutTrashed(),
            ],
            'layout_id' => [
                'sometimes',
                'string',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) use ($website): void {
                    if (! Layout::where('id', $value)->where('website_id', $website->id)->exists()) {
                        $fail('The selected layout does not belong to this website.');
                    }
                },
            ],
            'status' => ['sometimes', Rule::in(['active', 'archived'])],
        ];
    }
}
