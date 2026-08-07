<?php

namespace App\Http\Requests\WebsiteUser;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Services\WebsiteUserService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteUserRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Checked here (rather than a controller attribute) because the ability
     * depends on the requested role, which only lives in the request body,
     * and on the specific membership resolved from the {website}/{user} route pair.
     */
    public function authorize(WebsiteUserService $websiteUsers): bool
    {
        /** @var Website $website */
        $website = $this->route('website');
        /** @var User $targetUser */
        $targetUser = $this->route('user');

        $membership = $websiteUsers->findMembership($website, $targetUser);
        $newRole = WebsiteRole::tryFrom((string) $this->input('role'));

        return (bool) $this->user()?->can('update', [$membership, $newRole]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', Rule::enum(WebsiteRole::class)],
        ];
    }
}
