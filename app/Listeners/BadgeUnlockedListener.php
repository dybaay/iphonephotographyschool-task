<?php

namespace App\Listeners;

use App\Events\BadgeUnlockedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BadgeUnlockedListener
{

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlockedEvent $event): void
    {
        $user = $event->user;
        $badge_id = $event->badge_id;
        $user->badges()->syncWithoutDetaching([$badge_id => [
            'created_at' => now()
        ]
        ]);
    }
}
