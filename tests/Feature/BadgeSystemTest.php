<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use App\Services\BadgeService;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed badges for each test
        $this->seed(BadgeSeeder::class);
    }

    /**
     * Test that the badge seeder creates the exactly 3 requested badges.
     */
    public function test_badge_seeder_creates_correct_badges(): void
    {
        $this->assertDatabaseCount('badges', 3);

        $this->assertDatabaseHas('badges', [
            'name' => 'First AC',
            'description' => "Awarded on a user's first-ever accepted submission",
            'icon_class' => 'bi bi-award',
        ]);

        $this->assertDatabaseHas('badges', [
            'name' => 'Speed Demon',
            'description' => "Awarded for an accepted submission within 5 minutes of a contest's start time",
            'icon_class' => 'bi bi-lightning-charge-fill',
        ]);

        $this->assertDatabaseHas('badges', [
            'name' => 'Problem Slayer',
            'description' => 'Awarded once a user reaches 50 or more accepted submissions',
            'icon_class' => 'bi bi-shield-shaded',
        ]);
    }

    /**
     * Test that the first accepted submission awards the "First AC" badge.
     */
    public function test_first_ac_badge_is_awarded(): void
    {
        $user = User::factory()->create();
        $problem = Problem::factory()->create();

        $submission = Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problem->id,
            'code' => 'print(1)',
            'language' => 'python',
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);

        app(BadgeService::class)->checkAndAward($submission);

        $this->assertTrue($user->badges()->where('name', 'First AC')->exists());
        $this->assertDatabaseCount('user_badges', 1); // Only First AC should be awarded
    }

    /**
     * Test that a non-accepted submission does not award the "First AC" badge.
     */
    public function test_non_accepted_submission_does_not_award_first_ac(): void
    {
        $user = User::factory()->create();
        $problem = Problem::factory()->create();

        $submission = Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problem->id,
            'code' => 'print(1)',
            'language' => 'python',
            'status' => 'wrong_answer',
            'submitted_at' => now(),
        ]);

        app(BadgeService::class)->checkAndAward($submission);

        $this->assertFalse($user->badges()->where('name', 'First AC')->exists());
        $this->assertDatabaseCount('user_badges', 0);
    }

    /**
     * Test that a submission within 5 minutes of a contest's start time awards the "Speed Demon" badge.
     */
    public function test_speed_demon_badge_is_awarded(): void
    {
        $user = User::factory()->create();
        $problem = Problem::factory()->create();
        
        $contestStart = now()->subMinutes(10);
        $contest = Contest::create([
            'title' => 'Speed Contest',
            'starts_at' => $contestStart,
            'ends_at' => now()->addHour(),
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        // Submission at exactly 4 minutes after contest start
        $submission = Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problem->id,
            'contest_id' => $contest->id,
            'code' => 'print(1)',
            'language' => 'python',
            'status' => 'accepted',
            'submitted_at' => $contestStart->copy()->addMinutes(4),
        ]);

        app(BadgeService::class)->checkAndAward($submission);

        $this->assertTrue($user->badges()->where('name', 'Speed Demon')->exists());
        $this->assertTrue($user->badges()->where('name', 'First AC')->exists()); // Since it's also their first AC
        $this->assertDatabaseCount('user_badges', 2);
    }

    /**
     * Test that a submission after 5 minutes of a contest's start time does not award the "Speed Demon" badge.
     */
    public function test_speed_demon_badge_is_not_awarded_after_five_minutes(): void
    {
        $user = User::factory()->create();
        $problem = Problem::factory()->create();
        
        $contestStart = now()->subMinutes(10);
        $contest = Contest::create([
            'title' => 'Speed Contest',
            'starts_at' => $contestStart,
            'ends_at' => now()->addHour(),
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        // Submission at 6 minutes after contest start
        $submission = Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problem->id,
            'contest_id' => $contest->id,
            'code' => 'print(1)',
            'language' => 'python',
            'status' => 'accepted',
            'submitted_at' => $contestStart->copy()->addMinutes(6),
        ]);

        app(BadgeService::class)->checkAndAward($submission);

        $this->assertFalse($user->badges()->where('name', 'Speed Demon')->exists());
        $this->assertTrue($user->badges()->where('name', 'First AC')->exists()); // Still gets First AC
        $this->assertDatabaseCount('user_badges', 1);
    }

    /**
     * Test that reaching 50 accepted submissions awards the "Problem Slayer" badge.
     */
    public function test_problem_slayer_badge_is_awarded(): void
    {
        $user = User::factory()->create();
        $problem = Problem::factory()->create();

        // Create 49 accepted submissions
        for ($i = 0; $i < 49; $i++) {
            Submission::create([
                'user_id' => $user->id,
                'problem_id' => $problem->id,
                'code' => 'print(1)',
                'language' => 'python',
                'status' => 'accepted',
                'submitted_at' => now(),
            ]);
        }

        // Verify the user does not have Problem Slayer yet
        // We will call checkAndAward on the 49th submission, it shouldn't award Problem Slayer
        $lastSubmission = Submission::first();
        app(BadgeService::class)->checkAndAward($lastSubmission);
        $this->assertFalse($user->badges()->where('name', 'Problem Slayer')->exists());

        // Create the 50th accepted submission
        $fiftiethSubmission = Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problem->id,
            'code' => 'print(1)',
            'language' => 'python',
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);

        app(BadgeService::class)->checkAndAward($fiftiethSubmission);

        $this->assertTrue($user->badges()->where('name', 'Problem Slayer')->exists());
    }
}
