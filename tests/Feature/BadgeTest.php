<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed(DatabaseSeeder::class);
    }
    public function test_calculates_badge_when_user_unlocks_enough_achievements()
    {
        Event::fake([
            \App\Events\BadgeUnlockedEvent::class
        ]);

        $user = User::find(1);

        // Fire AchievementUnlocked event multiple times to unlock achievements
        Event::dispatch(new \App\Events\AchievementUnlockedEvent(1, $user));
        Event::dispatch(new \App\Events\AchievementUnlockedEvent(3, $user));
        Event::dispatch(new \App\Events\AchievementUnlockedEvent(5, $user));
        Event::dispatch(new \App\Events\AchievementUnlockedEvent(6, $user));

        $expected_badge = Badge::query()->firstWhere('achievement_count', 4);
        // Assert BadgeUnlocked event was fired
        Event::assertDispatched(\App\Events\BadgeUnlockedEvent::class, function ($event) use ($user, $expected_badge) {
            return $event->user->id === $user->id && $event->badge_id === $expected_badge->id;
        });
    }

}
