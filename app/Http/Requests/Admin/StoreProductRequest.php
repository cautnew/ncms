<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:products,slug'],
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
        ];
    }
}
