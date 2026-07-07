<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqItemRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'faq_category_id' => ['required', 'integer', 'exists:faq_categories,id'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'order' => ['required', 'integer', 'min:0'],
        ];
    }
}
