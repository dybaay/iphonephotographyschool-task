<?php

namespace App\Observers;

use App\Models\Badge;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(User $user): void
    {
        $badge = Badge::query()->firstWhere('achievement_count', 0);
        $user->badges()->attach($badge->id, [
            'created_at' => now()
        ]);
    }
}
