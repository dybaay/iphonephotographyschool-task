<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_check_if_first_lesson_watched_achievement_is_unlocked_on_first_lesson_watch()
    {
        Event::fake([
            \App\Events\AchievementUnlockedEvent::class
        ]);

        $user = User::find(1);

        $user->lessons()->attach(1, ['watched' => true]);
        Event::dispatch(new \App\Events\LessonWatched($user));
        $expected_achievement = Achievement::query()->where('type', 'lesson')
            ->where('action_count', 1)->first();

        Event::assertDispatched(\App\Events\AchievementUnlockedEvent::class, function ($event) use ($user, $expected_achievement) {
            return $event->user->id === $user->id && $event->achievement_id === $expected_achievement->id;
        });
    }

    public function test_check_if_first_comment_written_achievement_is_unlocked_on_first_comment_write()
    {
        Event::fake([
            \App\Events\AchievementUnlockedEvent::class
        ]);

        $user = User::find(1);

        $user->comments()->create(['body' => fake()->sentence()]);
        Event::dispatch(new \App\Events\CommentWritten($user));
        $expected_achievement = Achievement::query()->where('type', 'comment')
            ->where('action_count', 1)->first();

        Event::assertDispatched(\App\Events\AchievementUnlockedEvent::class, function ($event) use ($user, $expected_achievement) {
            return $event->user->id === $user->id && $event->achievement_id === $expected_achievement->id;
        });
    }

    public function test_calculates_achievement_when_user_watches_enough_lessons()
    {
        Event::fake([
           \App\Events\AchievementUnlockedEvent::class
        ]);

        $user = User::find(1);

        // Fire AchievementUnlocked event multiple times to unlock achievements
        $user->lessons()->attach(1, ['watched' => true]);
        Event::dispatch(new \App\Events\LessonWatched($user));
        $user->lessons()->attach(2, ['watched' => true]);
        Event::dispatch(new \App\Events\LessonWatched($user));
        $user->lessons()->attach(3, ['watched' => true]);
        Event::dispatch(new \App\Events\LessonWatched($user));
        $user->lessons()->attach(4, ['watched' => true]);
        Event::dispatch(new \App\Events\LessonWatched($user));
        $user->lessons()->attach(5, ['watched' => true]);
        Event::dispatch(new \App\Events\LessonWatched($user));

        $expected_achievement = Achievement::query()->where('type', 'lesson')
            ->where('action_count', 5)->first();

        Event::assertDispatched(\App\Events\AchievementUnlockedEvent::class, function ($event) use ($user, $expected_achievement) {
            return $event->user->id === $user->id && $event->achievement_id === $expected_achievement->id;
        });

    }

    public function test_calculates_achievement_when_user_writes_enough_comments()
    {
        Event::fake([
            \App\Events\AchievementUnlockedEvent::class
        ]);

        $user = User::find(1);

        // Fire AchievementUnlocked event multiple times to unlock achievements
        $user->comments()->create(['body' => fake()->sentence()]);
        Event::dispatch(new \App\Events\CommentWritten($user));
        $user->comments()->create(['body' => fake()->sentence()]);
        Event::dispatch(new \App\Events\CommentWritten($user));
        $user->comments()->create(['body' => fake()->sentence()]);
        Event::dispatch(new \App\Events\CommentWritten($user));

        $expected_achievement = Achievement::query()->where('type', 'comment')
            ->where('action_count', 3)->first();

        Event::assertDispatched(\App\Events\AchievementUnlockedEvent::class, function ($event) use ($user, $expected_achievement) {
            return $event->user->id === $user->id && $event->achievement_id === $expected_achievement->id;
        });

    }
}
