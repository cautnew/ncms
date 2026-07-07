<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandContentRequest extends FormRequest
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
            'story_history.title' => ['required', 'string', 'max:255'],
            'story_history.text1' => ['required', 'string'],
            'story_history.text2' => ['required', 'string'],
            'story_history.image' => ['required', 'string', 'max:2048'],
            'story_sustainability.title' => ['required', 'string', 'max:255'],
            'story_sustainability.text' => ['required', 'string'],
            'story_sustainability.image' => ['required', 'string', 'max:2048'],
            'stats' => ['required', 'array', 'min:1'],
            'stats.*.number' => ['required', 'string', 'max:20'],
            'stats.*.label' => ['required', 'string', 'max:255'],
            'values' => ['required', 'array', 'min:1'],
            'values.*.icon' => ['required', 'string', 'max:10'],
            'values.*.title' => ['required', 'string', 'max:255'],
            'values.*.text' => ['required', 'string'],
            'testimonial.quote' => ['required', 'string'],
            'testimonial.author' => ['required', 'string', 'max:255'],
            'cta.title' => ['required', 'string', 'max:255'],
            'cta.text' => ['required', 'string'],
            'cta.button_label' => ['required', 'string', 'max:255'],
            'cta.button_href' => ['required', 'string', 'max:255'],
        ];
    }
}
