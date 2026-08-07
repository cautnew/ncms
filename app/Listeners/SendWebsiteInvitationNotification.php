<?php

namespace App\Listeners;

use App\Events\WebsiteUserInvited;
use App\Notifications\WebsiteInvitationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWebsiteInvitationNotification implements ShouldQueue
{
    public function handle(WebsiteUserInvited $event): void
    {
        $event->membership->user->notify(
            new WebsiteInvitationNotification($event->membership, $event->inviter),
        );
    }
}
