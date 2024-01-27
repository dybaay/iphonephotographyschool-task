<?php

namespace App\Providers;

use App\Events\AchievementUnlockedEvent;
use App\Events\BadgeUnlockedEvent;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\BadgeAndAchievementUnlockedListener;
use App\Listeners\LessonWatchedAndCommentWrittenListener;
use App\Models\Comment;
use App\Models\User;
use App\Observers\CommentObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        CommentWritten::class => [
            LessonWatchedAndCommentWrittenListener::class
        ],

        LessonWatched::class => [
            LessonWatchedAndCommentWrittenListener::class
        ],

        BadgeUnlockedEvent::class => [
            BadgeAndAchievementUnlockedListener::class
        ],

        AchievementUnlockedEvent::class => [
            BadgeAndAchievementUnlockedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Comment::observe(CommentObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
