<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($this->page)
            ],
            'status' => ['sometimes', 'required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'content' => ['nullable', 'array'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'url'],
            'robots' => ['nullable', 'string', 'max:255'],
            'head_tags' => ['nullable', 'array'],
            'body_start_tags' => ['nullable', 'array'],
            'body_end_tags' => ['nullable', 'array'],
            'schema_jsonld' => ['nullable', 'array'],
            'template_class' => ['nullable', 'string', 'max:255'],
            'template_payload' => ['nullable', 'array'],
        ];
    }
}
