<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Database\Seeders\RoleSeeder;

class ScoreboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    /**
     * Test scoreboard routes accessibility.
     */
    public function test_scoreboard_access_and_approvals(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $unapprovedContest = Contest::create([
            'title' => 'Unapproved Contest',
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
            'is_active' => true,
            'is_approved' => false,
            'created_by' => $judge->id,
        ]);

        $approvedContest = Contest::create([
            'title' => 'Approved Contest',
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
            'is_active' => true,
            'is_approved' => true,
            'created_by' => $judge->id,
        ]);

        $contestant = User::factory()->create(['status' => 'approved']);
        $contestant->assignRole('Contestant');

        // 1. Visit unapproved contest scoreboard -> 403
        $response = $this->actingAs($contestant)->get(route('contests.scoreboard', $unapprovedContest));
        $response->assertStatus(403);

        // 2. Visit approved contest scoreboard -> 200
        $response = $this->actingAs($contestant)->get(route('contests.scoreboard', $approvedContest));
        $response->assertStatus(200);

        // 3. Get data for unapproved -> 403
        $response = $this->actingAs($contestant)->get(route('contests.scoreboard.data', $unapprovedContest));
        $response->assertStatus(403);

        // 4. Get data for approved -> 200
        $response = $this->actingAs($contestant)->get(route('contests.scoreboard.data', $approvedContest));
        $response->assertStatus(200);
    }

    /**
     * Test ICPC standings calculation logic.
     */
    public function test_icpc_scoreboard_calculation(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $problemA = Problem::create([
            'title' => 'Problem A',
            'slug' => 'problem-a',
            'statement' => 'Statement A',
            'difficulty' => 'easy',
            'is_published' => true,
            'created_by' => $judge->id,
        ]);

        $problemB = Problem::create([
            'title' => 'Problem B',
            'slug' => 'problem-b',
            'statement' => 'Statement B',
            'difficulty' => 'medium',
            'is_published' => true,
            'created_by' => $judge->id,
        ]);

        $contest = Contest::create([
            'title' => 'Scoreboard Contest',
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
            'is_active' => true,
            'is_approved' => true,
            'created_by' => $judge->id,
        ]);

        $contest->problems()->attach($problemA->id, ['label' => 'A']);
        $contest->problems()->attach($problemB->id, ['label' => 'B']);

        // Enrolled contestants
        $user1 = User::factory()->create(['status' => 'approved', 'name' => 'Alice']);
        $user1->assignRole('Contestant');
        $user2 = User::factory()->create(['status' => 'approved', 'name' => 'Bob']);
        $user2->assignRole('Contestant');

        $contest->participants()->attach($user1->id, ['joined_at' => now()]);
        $contest->participants()->attach($user2->id, ['joined_at' => now()]);

        // Alice: 
        // - Wrong answer on A at start + 5 mins
        // - Accepted on A at start + 10 mins (Penalty = 20 * 1 + 10 = 30)
        // - Accepted on B at start + 15 mins (Penalty = 15)
        // - Total A & B solved: 2 problems, Penalty: 30 + 15 = 45
        Submission::create([
            'user_id' => $user1->id,
            'problem_id' => $problemA->id,
            'contest_id' => $contest->id,
            'language' => 'cpp',
            'code' => 'xxx',
            'status' => 'wrong_answer',
            'submitted_at' => $contest->starts_at->copy()->addMinutes(5),
        ]);
        Submission::create([
            'user_id' => $user1->id,
            'problem_id' => $problemA->id,
            'contest_id' => $contest->id,
            'language' => 'cpp',
            'code' => 'xxx',
            'status' => 'accepted',
            'submitted_at' => $contest->starts_at->copy()->addMinutes(10),
        ]);
        Submission::create([
            'user_id' => $user1->id,
            'problem_id' => $problemB->id,
            'contest_id' => $contest->id,
            'language' => 'cpp',
            'code' => 'xxx',
            'status' => 'accepted',
            'submitted_at' => $contest->starts_at->copy()->addMinutes(15),
        ]);

        // Bob:
        // - Accepted A on first attempt at start + 25 mins (Penalty = 25)
        // - Total solved: 1 problem, Penalty: 25
        Submission::create([
            'user_id' => $user2->id,
            'problem_id' => $problemA->id,
            'contest_id' => $contest->id,
            'language' => 'cpp',
            'code' => 'xxx',
            'status' => 'accepted',
            'submitted_at' => $contest->starts_at->copy()->addMinutes(25),
        ]);

        // Get scoreboard data
        $response = $this->actingAs($user1)->get(route('contests.scoreboard.data', $contest));
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertCount(2, $data['rows']);

        // First row should be Alice (2 solved)
        $this->assertEquals('Alice', $data['rows'][0]['name']);
        $this->assertEquals(2, $data['rows'][0]['solve_count']);
        $this->assertEquals(45, $data['rows'][0]['total_penalty']);

        // Second row should be Bob (1 solved)
        $this->assertEquals('Bob', $data['rows'][1]['name']);
        $this->assertEquals(1, $data['rows'][1]['solve_count']);
        $this->assertEquals(25, $data['rows'][1]['total_penalty']);
    }
}
