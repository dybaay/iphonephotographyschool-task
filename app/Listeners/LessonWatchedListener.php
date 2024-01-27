<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonWatchedListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        $lesson_count = $user->watched()->count();

        $lesson_achievement = Achievement::query()
            ->where('type', 'lesson')
            ->where('action_count', $lesson_count)
            ->first();

        if ($lesson_achievement) {
            event(new AchievementUnlockedEvent($lesson_achievement->id, $user));
        }
    }
}
