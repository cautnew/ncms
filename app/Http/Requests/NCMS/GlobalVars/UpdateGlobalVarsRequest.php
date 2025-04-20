<?php

namespace App\Http\Requests\NCMS\GlobalVars;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGlobalVarsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'is_read_only' => 'boolean',
            'is_protected' => 'boolean',
            'user_id' => 'exists:users,id',
        ];
    }
}
