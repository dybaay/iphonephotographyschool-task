<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Lesson;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function achievementsArray(): array
    {
        return [
            [
                'type' => 'lesson',
                'action_count' => 1,
                'name' => 'First Lesson Watched'
            ],
            [
                'type' => 'lesson',
                'action_count' => 5,
                'name' => '5 Lessons Watched'
            ],
            [
                'type' => 'lesson',
                'action_count' => 10,
                'name' => '10 Lessons Watched'
            ],
            [
                'type' => 'lesson',
                'action_count' => 25,
                'name' => '25 Lessons Watched'
            ],
            [
                'type' => 'lesson',
                'action_count' => 50,
                'name' => '50 Lessons Watched'
            ],
            [
                'type' => 'comment',
                'action_count' => 1,
                'name' => 'First Comment Written'
            ],
            [
                'type' => 'comment',
                'action_count' => 3,
                'name' => '3 Comments Written'
            ],
            [
                'type' => 'comment',
                'action_count' => 5,
                'name' => '5 Comments Written'
            ],
            [
                'type' => 'comment',
                'action_count' => 10,
                'name' => '25 Comments Written'
            ],
            [
                'type' => 'comment',
                'action_count' => 20,
                'name' => '50 Comments Written'
            ]
        ];
    }

    public function badgesArray(): array
    {
        return [
            [
                'name' => 'Beginner',
                'achievement_count' => 0
            ],
            [
                'name' => 'Intermediate',
                'achievement_count' => 4
            ],
            [
                'name' => 'Advanced',
                'achievement_count' => 8
            ],
            [
                'name' => 'Master',
                'achievement_count' => 10
            ]
        ];
    }

    public function run(): void
    {
        Badge::factory()
            ->createMany($this->badgesArray());
        User::factory()->count(5)->create();
        Lesson::factory()
            ->count(20)
            ->create();
        Achievement::factory()
            ->createMany($this->achievementsArray());
    }
}
