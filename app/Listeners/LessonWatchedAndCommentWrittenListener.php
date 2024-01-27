<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Events\BadgeUnlockedEvent;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Badge;
use Illuminate\Support\Facades\Log;


class LessonWatchedAndCommentWrittenListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handleLessonsWatched(LessonWatched $event): void
    {
        $user = $event->user;
        $lesson_count = $user->watched()->count();

        $lesson_achievement = Achievement::query()
            ->where('type', 'lesson')
            ->where('count', $lesson_count)
            ->first();

        if ($lesson_achievement) {
            event(new AchievementUnlockedEvent($lesson_achievement->id, $user));
        }
    }

    public function handleCommentsWritten(CommentWritten $event): void
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

    public function calculateBadge($event): void
    {
        $user = $event->user;
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
    public function handle($event): void
    {
        if ($event instanceof LessonWatched) {
            $this->handleLessonsWatched($event);
        }

        if ($event instanceof CommentWritten) {
            $this->handleCommentsWritten($event);
        }
        $this->calculateBadge($event);
    }
}


