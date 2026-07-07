<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('products', 'slug')->ignore($this->route('product'))],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:50'],
            'old_price' => ['nullable', 'string', 'max:50'],
            'rating' => ['required', 'string', 'max:10'],
            'reviews' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'string', 'max:2048'],
            'description' => ['required', 'string'],
            'specs' => ['required', 'array', 'min:1'],
            'specs.*.label' => ['required', 'string', 'max:100'],
            'specs.*.value' => ['required', 'string', 'max:100'],
            'usage_text' => ['required', 'string'],
            'ingredients_text' => ['required', 'string'],
        ];
    }
}
