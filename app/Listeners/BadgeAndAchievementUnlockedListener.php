<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Events\BadgeUnlockedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BadgeAndAchievementUnlockedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handleAchievementUnlocked(AchievementUnlockedEvent $event): void
    {
        $user = $event->user;
        $achievement_id = $event->achievement_id;
        $user->achievements()->syncWithoutDetaching([$achievement_id]);
    }

    public function handleBadgeUnlocked(BadgeUnlockedEvent $event): void
    {
        $user = $event->user;
        $badge_id = $event->badge_id;
        $user->badges()->syncWithoutDetaching([$badge_id]);
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        if ($event instanceof BadgeUnlockedEvent)
        {
            $this->handleBadgeUnlocked($event);
        }

        if ($event instanceof AchievementUnlockedEvent)
        {
            $this->handleAchievementUnlocked($event);
        }
    }
}
