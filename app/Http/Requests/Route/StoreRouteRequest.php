<?php

namespace App\Http\Requests\Route;

use App\Enums\HttpMethod;
use App\Enums\RouteDestinationType;
use App\Models\Route;
use App\Models\Website;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRouteRequest extends FormRequest
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
            'path' => ['required', 'string', 'max:2048'],
            'http_method' => ['sometimes', Rule::enum(HttpMethod::class)],
            'http_status' => ['sometimes', 'integer', 'between:100,599'],
            'destination_type' => ['required', Rule::enum(RouteDestinationType::class)],
            'page_id' => [
                'required_if:destination_type,'.RouteDestinationType::Page->value,
                'nullable',
                'string',
                'uuid',
                Rule::exists('pages', 'id')->where('website_id', $website->id),
            ],
            'asset_id' => [
                'required_if:destination_type,'.RouteDestinationType::Asset->value,
                'nullable',
                'string',
                'uuid',
                Rule::exists('assets', 'id')->where('website_id', $website->id),
            ],
            'redirect_to_route_id' => [
                'required_if:destination_type,'.RouteDestinationType::Redirect->value,
                'nullable',
                'string',
                'uuid',
                Rule::exists('routes', 'id')->where('website_id', $website->id),
            ],
        ];
    }

    /**
     * @param  Validator  $validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->validatePathUnique($validator);
        });
    }

    /**
     * path+http_method must be unique per website — checked against the
     * *normalized* path (matching Route::normalizePath()), since the raw
     * input might differ in trailing slashes/casing of separators from what
     * ends up stored.
     */
    private function validatePathUnique(Validator $validator): void
    {
        $path = $this->input('path');

        if (! is_string($path)) {
            return;
        }

        /** @var Website $website */
        $website = $this->route('website');
        $method = $this->filled('http_method') ? $this->input('http_method') : HttpMethod::Get->value;

        $exists = Route::query()
            ->forWebsite($website->id)
            ->where('path', Route::normalizePath($path))
            ->where('http_method', $method)
            ->exists();

        if ($exists) {
            $validator->errors()->add('path', 'A route for this path and HTTP method already exists on this website.');
        }
    }
}
