<?php

namespace App\Http\Requests\Layout;

use App\Models\Layout;
use App\Models\Website;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLayoutRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Website $website */
        $website = $this->route('website');
        /** @var Layout $layout */
        $layout = $this->route('layout');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('layouts', 'slug')
                    ->where(fn ($query) => $query->where('website_id', $website->id))
                    ->ignore($layout->id)
                    ->withoutTrashed(),
            ],
            'description' => ['sometimes', 'nullable', 'string'],
            'schema' => ['sometimes', 'nullable', 'array'],
            'status' => ['sometimes', Rule::in(['draft', 'active', 'archived'])],
        ];
    }
}
