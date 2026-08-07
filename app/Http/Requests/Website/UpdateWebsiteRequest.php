<?php

namespace App\Http\Requests\Website;

use App\Models\Website;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteRequest extends FormRequest
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

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'domain' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('websites', 'domain')
                    ->where(fn ($query) => $query->where('subdomain', $this->input('subdomain') ?? $website->subdomain))
                    ->ignore($website->id)
                    ->withoutTrashed(),
            ],
            // 'nullable' is required because Laravel's ConvertEmptyStringsToNull middleware
            // turns an explicit subdomain: "" (meaning "root domain, no subdomain") into null
            // before validation runs.
            'subdomain' => ['sometimes', 'nullable', 'string', 'max:255'],
            'locale' => ['sometimes', 'string', 'max:10'],
            'timezone' => ['sometimes', 'nullable', 'string', 'max:64'],
            'status' => ['sometimes', Rule::in(['active', 'suspended', 'archived'])],
        ];
    }
}
