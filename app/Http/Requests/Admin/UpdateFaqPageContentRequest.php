<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqPageContentRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'intro.title' => ['required', 'string', 'max:255'],
            'intro.text' => ['required', 'string'],
            'cta.title' => ['required', 'string', 'max:255'],
            'cta.text' => ['required', 'string'],
            'cta.button_label' => ['required', 'string', 'max:255'],
            'cta.button_href' => ['required', 'string', 'max:255'],
        ];
    }
}
