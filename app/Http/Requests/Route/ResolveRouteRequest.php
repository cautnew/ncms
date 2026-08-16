<?php

namespace App\Http\Requests\Route;

use App\Enums\HttpMethod;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResolveRouteRequest extends FormRequest
{
    /**
     * Public endpoint — no auth:sanctum, resolving a URL is exactly what a
     * live site's frontend needs before it even knows if there's anything there.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'path' => ['required', 'string', 'max:2048'],
            'method' => ['sometimes', Rule::enum(HttpMethod::class)],
        ];
    }

    /**
     * The HTTP method to resolve against — not to be confused with the base
     * Request::method(), which reports this request's own (always GET) verb.
     */
    public function targetMethod(): HttpMethod
    {
        return $this->filled('method') ? HttpMethod::from($this->string('method')->toString()) : HttpMethod::Get;
    }
}
