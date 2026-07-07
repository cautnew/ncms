<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticlesPageContentRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'intro.title' => ['required', 'string', 'max:255'],
            'intro.text' => ['required', 'string'],
        ];
    }
}
