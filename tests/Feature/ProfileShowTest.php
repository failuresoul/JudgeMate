<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get('/profile/show');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_own_profile_page_with_stats(): void
    {
        $user = User::factory()->create([
            'status' => 'approved',
        ]);

        $judge = User::factory()->create([
            'status' => 'approved',
        ]);

        // Create some problems
        $problemA = Problem::factory()->create(['title' => 'Problem Alpha', 'created_by' => $judge->id]);
        $problemB = Problem::factory()->create(['title' => 'Problem Beta', 'created_by' => $judge->id]);

        // Submissions for user:
        // 2 accepted submissions for Problem Alpha
        Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problemA->id,
            'status' => 'accepted',
            'verdict_message' => 'Passed all test cases.',
            'language' => 'cpp',
            'code' => 'int main() {}',
            'submitted_at' => now()->subMinutes(5),
        ]);

        Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problemA->id,
            'status' => 'accepted',
            'verdict_message' => 'Another pass.',
            'language' => 'cpp',
            'code' => 'int main() { return 0; }',
            'submitted_at' => now()->subMinutes(4),
        ]);

        // 1 accepted submission for Problem Beta
        Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problemB->id,
            'status' => 'accepted',
            'verdict_message' => 'Pass.',
            'language' => 'python',
            'code' => 'print("hello")',
            'submitted_at' => now()->subMinutes(3),
        ]);

        // 1 wrong_answer submission for Problem Beta
        Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problemB->id,
            'status' => 'wrong_answer',
            'verdict_message' => 'Wrong on testcase 1.',
            'language' => 'python',
            'code' => 'print("wrong")',
            'submitted_at' => now()->subMinutes(2),
        ]);

        // Access own profile page
        $response = $this->actingAs($user)->get('/profile/show');

        $response->assertOk();
        $response->assertSee($user->name);
        $response->assertSee('@' . $user->username);
        // Total accepted count = 3
        $response->assertSee('3'); 
        // Distinct solved count = 2 (Problem Alpha, Problem Beta)
        $response->assertSee('2'); 

        // Verify recent submissions listed
        $response->assertSee('Problem Alpha');
        $response->assertSee('Problem Beta');
        $response->assertSee('Wrong Answer');
        $response->assertSee('Accepted');
    }

    public function test_authenticated_user_can_view_another_users_profile(): void
    {
        $user1 = User::factory()->create(['status' => 'approved']);
        $user2 = User::factory()->create(['status' => 'approved']);

        $response = $this->actingAs($user1)->get('/profile/show/' . $user2->id);

        $response->assertOk();
        $response->assertSee($user2->name);
        $response->assertSee('@' . $user2->username);
    }
}
