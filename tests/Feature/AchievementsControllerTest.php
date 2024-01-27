<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AchievementsControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_returns_user_achievement_data(): void
    {
        //This test is when the user has no achievements
        $user = User::find(1);
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200)
            ->assertJson([
                'unlocked_achievements' => [],
                'next_available_achievements' => [
                    "First Lesson Watched",
                    "5 Lessons Watched",
                    "10 Lessons Watched",
                    "25 Lessons Watched",
                    "50 Lessons Watched",
                    "First Comment Written",
                    "3 Comments Written",
                    "5 Comments Written",
                    "25 Comments Written",
                    "50 Comments Written"
                ],
                'current_badge' => 'Beginner',
                'next_badge' => 'Intermediate',
                'remaining_achievements_to_unlock_next_badge' => 4,
            ]);
    }
}