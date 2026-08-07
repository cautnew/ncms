<?php

use App\DTOs\WebsiteUser\InviteWebsiteUserData;
use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Notifications\WebsiteInvitationNotification;
use App\Services\WebsiteUserService;
use Illuminate\Support\Facades\Notification;

/**
 * Exercises WebsiteUserService::invite() directly — Service + Repository +
 * Event + Notification — with no HTTP layer, covering both branches of "does
 * an account already exist for this email".
 */
it('invites a brand-new email by provisioning an account and sending the invitation notification', function () {
    Notification::fake();
    $website = Website::factory()->create();
    $inviter = User::factory()->create();

    $membership = app(WebsiteUserService::class)->invite($website, $inviter, new InviteWebsiteUserData(
        email: 'brand-new@example.com',
        name: 'Brand New',
        role: WebsiteRole::Editor,
    ));

    $invitee = User::where('email', 'brand-new@example.com')->sole();
    expect($invitee->name)->toBe('Brand New');
    expect($membership->user_id)->toBe($invitee->id);
    expect($membership->role)->toBe(WebsiteRole::Editor);
    expect($membership->invited_by)->toBe($inviter->id);

    Notification::assertSentTo($invitee, WebsiteInvitationNotification::class);
});

it('invites an existing email by reusing their account rather than creating a duplicate', function () {
    Notification::fake();
    $website = Website::factory()->create();
    $inviter = User::factory()->create();
    $existing = User::factory()->create(['email' => 'already-here@example.com']);

    $membership = app(WebsiteUserService::class)->invite($website, $inviter, new InviteWebsiteUserData(
        email: 'already-here@example.com',
        name: null,
        role: WebsiteRole::Viewer,
    ));

    expect(User::where('email', 'already-here@example.com')->count())->toBe(1);
    expect($membership->user_id)->toBe($existing->id);

    Notification::assertSentTo($existing, WebsiteInvitationNotification::class);
});
