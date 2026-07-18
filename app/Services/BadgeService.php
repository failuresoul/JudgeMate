<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Submission;
use App\Notifications\BadgeEarned;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Check badge conditions and award badges if applicable.
     */
    public function checkAndAward(Submission $submission): void
    {
        // Badges are only awarded on accepted submissions
        if ($submission->status !== 'accepted') {
            return;
        }

        $user = $submission->user;
        if (!$user) {
            return;
        }

        // 1. First AC: Awarded on a user's first-ever accepted submission.
        $this->awardBadgeByName($user, 'First AC');

        // 2. Speed Demon: Awarded for an accepted submission within 5 minutes of a contest's start time.
        if ($submission->contest_id !== null) {
            // Ensure contest is loaded
            $contest = $submission->contest ?? $submission->load('contest')->contest;
            if ($contest && $contest->starts_at && $submission->submitted_at) {
                $submitted = $submission->submitted_at;
                $starts = $contest->starts_at;
                
                // Must be at or after start time, and within 5 minutes
                if ($submitted->gte($starts) && $submitted->lte($starts->copy()->addMinutes(5))) {
                    $this->awardBadgeByName($user, 'Speed Demon');
                }
            }
        }

        // 3. Problem Slayer: Awarded once a user reaches 50 or more accepted submissions.
        $acceptedCount = Submission::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->count();

        if ($acceptedCount >= 50) {
            $this->awardBadgeByName($user, 'Problem Slayer');
        }
    }

    /**
     * Helper to award a badge by its name safely (avoiding duplicates).
     */
    private function awardBadgeByName($user, string $badgeName): void
    {
        $badge = Badge::where('name', $badgeName)->first();
        if (!$badge) {
            return;
        }

        $exists = DB::table('user_badges')
            ->where('user_id', $user->id)
            ->where('badge_id', $badge->id)
            ->exists();

        if (!$exists) {
            $user->badges()->attach($badge->id, ['awarded_at' => now()]);
            $user->notify(new BadgeEarned($badge));
        }
    }
}
