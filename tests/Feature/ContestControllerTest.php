<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\Problem;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Support\Facades\Http;

class ContestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);

        // Fake Kontests API calls to prevent real internet requests during testing
        Http::fake([
            'https://kontests.net/api/v1/all' => Http::response([
                [
                    'name' => 'CF Round 999',
                    'url' => 'https://codeforces.com',
                    'start_time' => '2026-07-10T17:00:00.000Z',
                    'end_time' => '2026-07-10T19:00:00.000Z',
                    'duration' => '7200.0',
                    'site' => 'CodeForces',
                    'status' => 'BEFORE'
                ]
            ], 200)
        ]);
    }

    /**
     * Test contests access visibility based on approval status and roles.
     */
    public function test_contests_visibility_and_public_access(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $approvedContest = Contest::create([
            'title' => 'Approved Contest',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
            'is_active' => true,
            'is_approved' => true,
            'created_by' => $judge->id,
        ]);

        $pendingContest = Contest::create([
            'title' => 'Pending Contest',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
            'is_active' => true,
            'is_approved' => false,
            'created_by' => $judge->id,
        ]);

        // 1. Guest is redirected to login
        $this->get(route('contests.index'))->assertRedirect('/login');
        $this->get(route('contests.show', $approvedContest))->assertRedirect('/login');

        // 2. Contestant can only see approved contests
        $contestant = User::factory()->create(['status' => 'approved']);
        $contestant->assignRole('Contestant');

        $response = $this->actingAs($contestant)->get(route('contests.index'));
        $response->assertStatus(200);
        $response->assertSee('Approved Contest');
        $response->assertDontSee('Pending Contest');

        $this->actingAs($contestant)
            ->get(route('contests.show', $pendingContest))
            ->assertStatus(403);

        // 3. Creator Judge can see approved and their own pending contests
        $response = $this->actingAs($judge)->get(route('contests.index'));
        $response->assertStatus(200);
        $response->assertSee('Approved Contest');
        $response->assertSee('Pending Contest');

        $this->actingAs($judge)
            ->get(route('contests.show', $pendingContest))
            ->assertStatus(200);

        // Another Judge cannot see the pending contest
        $otherJudge = User::factory()->create(['status' => 'approved']);
        $otherJudge->assignRole('ProblemSetter');

        $response = $this->actingAs($otherJudge)->get(route('contests.index'));
        $response->assertDontSee('Pending Contest');

        $this->actingAs($otherJudge)
            ->get(route('contests.show', $pendingContest))
            ->assertStatus(403);

        // 4. Admin can see all contests
        $admin = User::factory()->create(['status' => 'approved']);
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->get(route('contests.index'));
        $response->assertSee('Approved Contest');
        $response->assertSee('Pending Contest');

        $this->actingAs($admin)
            ->get(route('contests.show', $pendingContest))
            ->assertStatus(200);
    }

    /**
     * Test role based CRUD controls.
     */
    public function test_role_based_crud_controls(): void
    {
        $contestant = User::factory()->create(['status' => 'approved']);
        $contestant->assignRole('Contestant');

        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $contest = Contest::create([
            'title' => 'Contest A',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
            'created_by' => $judge->id,
        ]);

        // 1. Contestants are blocked from all CRUD
        $this->actingAs($contestant)->get(route('contests.create'))->assertRedirect(route('dashboard'));
        $this->actingAs($contestant)->post(route('contests.store'), ['title' => 'Fail'])->assertRedirect(route('dashboard'));
        $this->actingAs($contestant)->get(route('contests.edit', $contest))->assertRedirect(route('dashboard'));
        $this->actingAs($contestant)->put(route('contests.update', $contest), ['title' => 'Fail'])->assertRedirect(route('dashboard'));
        $this->actingAs($contestant)->delete(route('contests.destroy', $contest))->assertRedirect(route('dashboard'));

        // 2. Admins are blocked from creating contests
        $admin = User::factory()->create(['status' => 'approved']);
        $admin->assignRole('Admin');

        $this->actingAs($admin)->get(route('contests.create'))->assertRedirect(route('dashboard'));
        $this->actingAs($admin)->post(route('contests.store'), ['title' => 'Fail'])->assertRedirect(route('dashboard'));
    }

    /**
     * Test Judge CRUD workflows, sequential labeling, and Admin approval.
     */
    public function test_judge_crud_workflow_and_admin_approval(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $prob1 = Problem::factory()->create(['title' => 'Binary Search', 'created_by' => $judge->id]);
        $prob2 = Problem::factory()->create(['title' => 'Dynamic Programming', 'created_by' => $judge->id]);

        // 1. Judge creates contest with validation errors (ends_at before starts_at)
        $this->actingAs($judge)
            ->post(route('contests.store'), [
                'title' => 'Contest X',
                'starts_at' => now()->addDay(),
                'ends_at' => now()->addDay()->subHours(2),
                'problems' => [$prob1->id, $prob2->id]
            ])->assertSessionHasErrors(['ends_at']);

        // 2. Judge creates contest successfully
        $startsAt = now()->addDay()->format('Y-m-d H:i:s');
        $endsAt = now()->addDay()->addHours(4)->format('Y-m-d H:i:s');

        $this->actingAs($judge)
            ->post(route('contests.store'), [
                'title' => 'Contest Y',
                'description' => 'Contest description',
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'is_active' => '1',
                'problems' => [$prob2->id, $prob1->id] // DP first, BS second
            ])->assertRedirect(route('contests.index'));

        $contest = Contest::where('title', 'Contest Y')->first();
        $this->assertNotNull($contest);
        $this->assertFalse($contest->is_approved); // Should be pending by default

        // Verify sequential problem labels
        $problems = $contest->problems;
        $this->assertCount(2, $problems);
        $this->assertEquals('Dynamic Programming', $problems->first()->title);
        $this->assertEquals('A', $problems->first()->pivot->label);
        $this->assertEquals('Binary Search', $problems->last()->title);
        $this->assertEquals('B', $problems->last()->pivot->label);

        // 3. Another judge cannot edit or delete this contest
        $otherJudge = User::factory()->create(['status' => 'approved']);
        $otherJudge->assignRole('ProblemSetter');

        $this->actingAs($otherJudge)->get(route('contests.edit', $contest))->assertStatus(403);
        $this->actingAs($otherJudge)->put(route('contests.update', $contest), ['title' => 'Hack'])->assertStatus(403);
        $this->actingAs($otherJudge)->delete(route('contests.destroy', $contest))->assertStatus(403);

        // 4. Admin approves the contest
        $admin = User::factory()->create(['status' => 'approved']);
        $admin->assignRole('Admin');

        $this->actingAs($admin)
            ->post(route('contests.approve', $contest))
            ->assertRedirect(route('contests.index'));

        $contest->refresh();
        $this->assertTrue($contest->is_approved);
    }

    /**
     * Test contestant registration for an approved contest.
     */
    public function test_contestant_can_register_for_contest(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $contest = Contest::create([
            'title' => 'Approved Contest',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
            'is_active' => true,
            'is_approved' => true,
            'created_by' => $judge->id,
        ]);

        $contestant = User::factory()->create(['status' => 'approved']);
        $contestant->assignRole('Contestant');

        // 1. Visit index - should see 'Register' button
        $response = $this->actingAs($contestant)->get(route('contests.index'));
        $response->assertStatus(200);
        $response->assertSee('Register');

        // 2. Perform registration
        $response = $this->actingAs($contestant)
            ->post(route('contests.register', $contest));
        
        $response->assertRedirect(route('contests.show', $contest));
        $this->assertTrue($contest->participants->contains($contestant->id));

        // 3. Visit index again - should see 'Enter' button instead of 'Register'
        $response = $this->actingAs($contestant)->get(route('contests.index'));
        $response->assertStatus(200);
        $response->assertSee('Enter');
        $response->assertDontSee('Register');
    }
}
