<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeContentRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'hero.title' => ['required', 'string', 'max:255'],
            'hero.subtitle' => ['required', 'string'],
            'hero.button_label' => ['required', 'string', 'max:255'],
            'hero.button_href' => ['required', 'string', 'max:255'],
            'info_blocks' => ['required', 'array', 'min:1'],
            'info_blocks.*.icon' => ['required', 'string', 'max:10'],
            'info_blocks.*.title' => ['required', 'string', 'max:255'],
            'info_blocks.*.text' => ['required', 'string'],
            'cta.title' => ['required', 'string', 'max:255'],
            'cta.text' => ['required', 'string'],
            'cta.button_label' => ['required', 'string', 'max:255'],
            'cta.button_href' => ['required', 'string', 'max:255'],
        ];
    }
}
