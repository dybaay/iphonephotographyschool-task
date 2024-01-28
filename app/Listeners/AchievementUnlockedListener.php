<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Events\BadgeUnlockedEvent;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AchievementUnlockedListener
{
    /**
     * Calculates the badge.
     */
    public function calculateBadge($user): void
    {
        $achievement_count = $user->achievements()->count();
        $existing_badge = $user->currentBadge();

        if ($existing_badge->achievement_count === $achievement_count) {
            return;
        }
        $badge = Badge::query()
            ->where('achievement_count', '>', 0)
            ->where('achievement_count', $achievement_count)
            ->first();

        if ($badge) {
            event(new BadgeUnlockedEvent($badge->id, $user));
        }
    }

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlockedEvent $event): void
    {
        $user = $event->user;
        $achievement_id = $event->achievement_id;
        $user->achievements()->syncWithoutDetaching([$achievement_id => [
            'created_at' => now()
        ]]);
        $this->calculateBadge($user);
    }
}
