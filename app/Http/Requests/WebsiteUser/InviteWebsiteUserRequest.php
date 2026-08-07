<?php

namespace App\Http\Requests\WebsiteUser;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteWebsiteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Checked here (rather than a controller attribute) because the ability
     * depends on the requested role, which only lives in the request body.
     */
    public function authorize(): bool
    {
        /** @var Website $website */
        $website = $this->route('website');
        $role = WebsiteRole::tryFrom((string) $this->input('role'));

        return (bool) $this->user()?->can('create', [WebsiteUser::class, $website, $role]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string|Closure>
     */
    public function rules(): array
    {
        /** @var Website $website */
        $website = $this->route('website');

        return [
            'email' => [
                'required',
                'email',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($website): void {
                    $existing = User::where('email', $value)->first();

                    if ($existing !== null && $website->websiteUsers()->where('user_id', $existing->id)->exists()) {
                        $fail('This user is already a member of the website.');
                    }
                },
            ],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'role' => ['required', Rule::enum(WebsiteRole::class)],
        ];
    }
}
