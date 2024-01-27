<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Events\CommentWritten;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentWrittenListener
{
    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $user = $event->user;
        $comment_count = $user->comments()->count();

        $comment_achievement = Achievement::query()
            ->where('type', 'comment')
            ->where('count', $comment_count)
            ->first();

        if ($comment_achievement) {
            event(new AchievementUnlockedEvent($comment_achievement->id, $user));
        }
    }
}
