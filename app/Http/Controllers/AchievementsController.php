<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AchievementsController extends Controller
{
    public function index(User $user): JsonResponse
    {
        $achievements = Achievement::query()
            ->pluck('name')
            ->toArray();
        $badges = Badge::query()
            ->orderBy('achievement_count')
            ->pluck('achievement_count')
            ->toArray();
        $unlocked_achievements = $user->achievements()
            ->pluck('name')
            ->toArray();
        $next_available_achievements = array_values(array_diff($achievements, $unlocked_achievements));
        $current_badge = $user->currentBadge()->name;
        $current_badge_index = array_search($user->currentBadge()->achievement_count, $badges);
        $next_badge = '';
        if ($current_badge_index !== false && isset($badges[$current_badge_index + 1])) {
            $next_badge = Badge::query()
                ->firstWhere('achievement_count', $badges[$current_badge_index + 1]);
        }
        $remaining_achievements_to_unlock_next_badge = $next_badge->achievement_count - $user->achievements()->count();

        return response()->json([
            'unlocked_achievements' => $unlocked_achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => $current_badge,
            'next_badge' => $next_badge->name,
            'remaining_achievements_to_unlock_next_badge' => $remaining_achievements_to_unlock_next_badge
        ]);
    }
}
