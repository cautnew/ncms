<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('articles', 'slug')->ignore($this->route('article'))],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'category' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'date'],
            'views' => ['required', 'string', 'max:50'],
            'image' => ['required', 'string', 'max:2048'],
        ];
    }
}
