<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:articles,slug'],
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
