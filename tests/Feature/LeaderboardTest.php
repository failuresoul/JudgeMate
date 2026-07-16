<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed badges for testing
        $this->seed(BadgeSeeder::class);
    }

    /**
     * Test that guests can access the leaderboard page.
     */
    public function test_guest_can_access_leaderboard(): void
    {
        $response = $this->get('/leaderboard');

        $response->assertOk();
        $response->assertSee('Global Leaderboard');
        $response->assertSee('Log In');
    }

    /**
     * Test that authenticated users can access the leaderboard.
     */
    public function test_authenticated_user_can_access_leaderboard(): void
    {
        $user = User::factory()->create([
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->get('/leaderboard');

        $response->assertOk();
        $response->assertSee('Global Leaderboard');
        $response->assertSee('@' . $user->username);
    }

    /**
     * Test that leaderboard correctly calculates distinct solved problems count and orders users.
     */
    public function test_leaderboard_lists_users_in_correct_order_with_solved_counts(): void
    {
        // Create 3 users
        $user1 = User::factory()->create(['name' => 'Contestant One', 'username' => 'user1', 'status' => 'approved']);
        $user2 = User::factory()->create(['name' => 'Contestant Two', 'username' => 'user2', 'status' => 'approved']);
        $user3 = User::factory()->create(['name' => 'Contestant Three', 'username' => 'user3', 'status' => 'approved']);

        // Create 3 problems
        $problemA = Problem::factory()->create();
        $problemB = Problem::factory()->create();
        $problemC = Problem::factory()->create();

        // User1 solves 3 distinct problems
        Submission::create(['user_id' => $user1->id, 'problem_id' => $problemA->id, 'status' => 'accepted', 'language' => 'python', 'code' => 'print(1)']);
        Submission::create(['user_id' => $user1->id, 'problem_id' => $problemB->id, 'status' => 'accepted', 'language' => 'python', 'code' => 'print(1)']);
        // Multiple accepted submissions for the same problem should only count once
        Submission::create(['user_id' => $user1->id, 'problem_id' => $problemC->id, 'status' => 'accepted', 'language' => 'python', 'code' => 'print(1)']);
        Submission::create(['user_id' => $user1->id, 'problem_id' => $problemC->id, 'status' => 'accepted', 'language' => 'python', 'code' => 'print(1)']);

        // User2 solves 1 distinct problem
        Submission::create(['user_id' => $user2->id, 'problem_id' => $problemA->id, 'status' => 'accepted', 'language' => 'python', 'code' => 'print(1)']);
        Submission::create(['user_id' => $user2->id, 'problem_id' => $problemB->id, 'status' => 'wrong_answer', 'language' => 'python', 'code' => 'print(1)']); // WA not counted

        // User3 solves 2 distinct problems
        Submission::create(['user_id' => $user3->id, 'problem_id' => $problemA->id, 'status' => 'accepted', 'language' => 'python', 'code' => 'print(1)']);
        Submission::create(['user_id' => $user3->id, 'problem_id' => $problemB->id, 'status' => 'accepted', 'language' => 'python', 'code' => 'print(1)']);

        $response = $this->get('/leaderboard');

        $response->assertOk();

        // Check if usernames are present
        $response->assertSee('Contestant One');
        $response->assertSee('Contestant Two');
        $response->assertSee('Contestant Three');

        // Check counts
        $response->assertSee('3 Solved');
        $response->assertSee('2 Solved');
        $response->assertSee('1 Solved');

        // Verify the HTML order of users based on ranking order
        $content = $response->getContent();
        $pos1 = strpos($content, 'Contestant One');
        $pos2 = strpos($content, 'Contestant Three');
        $pos3 = strpos($content, 'Contestant Two');

        $this->assertTrue($pos1 < $pos2, 'User 1 should be ranked higher than User 3');
        $this->assertTrue($pos2 < $pos3, 'User 3 should be ranked higher than User 2');
    }

    /**
     * Test that user badges are displayed on the leaderboard.
     */
    public function test_leaderboard_displays_user_badges(): void
    {
        $user = User::factory()->create(['name' => 'Decorated User', 'status' => 'approved']);
        $badge = Badge::where('name', 'First AC')->first();
        
        $user->badges()->attach($badge->id, ['awarded_at' => now()]);

        $response = $this->get('/leaderboard');

        $response->assertOk();
        $response->assertSee('Decorated User');
        $response->assertSee('First AC');
        $response->assertSee($badge->icon_class);
    }

    /**
     * Test that results are paginated by 20.
     */
    public function test_leaderboard_paginates_by_20(): void
    {
        User::factory()->count(25)->create(['status' => 'approved']);

        $response = $this->get('/leaderboard');

        $response->assertOk();
        
        // With 25 users, we should have pagination links and only 20 users on the first page
        $response->assertSee('Next');
    }
}
