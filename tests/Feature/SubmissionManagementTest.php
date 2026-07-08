<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Problem;
use App\Jobs\JudgeSubmission;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SubmissionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    /**
     * Test that guest users are blocked from submission routes.
     */
    public function test_guest_cannot_view_submission_form_or_submit(): void
    {
        $creator = User::factory()->create();
        $problem = Problem::factory()->create([
            'created_by' => $creator->id
        ]);

        $this->get(route('problems.submit', $problem))
            ->assertRedirect('/login');

        $this->post(route('problems.submissions.store', $problem), [
            'language' => 'cpp',
            'code'     => 'int main() {}',
        ])->assertRedirect('/login');
    }

    /**
     * Test that any logged in and approved user can submit a solution successfully.
     */
    public function test_contestant_can_view_submission_form_and_submit_successfully(): void
    {
        Queue::fake();

        $contestant = User::factory()->create(['status' => 'approved']);
        $contestant->assignRole('Contestant');

        $creator = User::factory()->create();
        $problem = Problem::factory()->create([
            'created_by' => $creator->id
        ]);

        // 1. Check form rendering
        $this->actingAs($contestant)
            ->get(route('problems.submit', $problem))
            ->assertStatus(200);

        // 2. Post code submission
        $response = $this->actingAs($contestant)
            ->post(route('problems.submissions.store', $problem), [
                'language' => 'cpp',
                'code'     => '#include <iostream>\nint main() { std::cout << "Hello"; }',
            ]);

        $response->assertRedirect(route('problems.show', $problem));
        
        $this->assertDatabaseHas('submissions', [
            'user_id'    => $contestant->id,
            'problem_id' => $problem->id,
            'language'   => 'cpp',
            'status'     => 'pending',
        ]);

        // Verify JudgeSubmission job was dispatched with the saved submission
        Queue::assertPushed(JudgeSubmission::class, function ($job) use ($contestant, $problem) {
            return $job->submission->user_id === $contestant->id &&
                   $job->submission->problem_id === $problem->id &&
                   $job->submission->status === 'pending';
        });
    }

    /**
     * Test submission fields validation checks.
     */
    public function test_submission_form_validation(): void
    {
        $contestant = User::factory()->create(['status' => 'approved']);
        $contestant->assignRole('Contestant');

        $creator = User::factory()->create();
        $problem = Problem::factory()->create([
            'created_by' => $creator->id
        ]);

        // Invalid language
        $this->actingAs($contestant)
            ->post(route('problems.submissions.store', $problem), [
                'language' => 'rust',
                'code'     => 'fn main() {}',
            ])->assertSessionHasErrors(['language']);

        // Missing code
        $this->actingAs($contestant)
            ->post(route('problems.submissions.store', $problem), [
                'language' => 'python',
                'code'     => '',
            ])->assertSessionHasErrors(['code']);
    }

    /**
     * Test the JudgeSubmission job class execution flow.
     */
    public function test_judge_submission_job_flow(): void
    {
        $contestant = User::factory()->create(['status' => 'approved']);
        $problem = Problem::factory()->create([
            'created_by' => User::factory()->create()->id
        ]);

        // Add a hidden testcase
        \App\Models\TestCase::create([
            'problem_id'      => $problem->id,
            'input'           => '1 2',
            'expected_output' => '3',
            'is_hidden'       => true,
        ]);

        $submission = \App\Models\Submission::create([
            'user_id'      => $contestant->id,
            'problem_id'   => $problem->id,
            'code'         => 'Correct solution code without any fail/error words.',
            'language'     => 'python',
            'status'       => 'pending',
            'submitted_at' => now(),
        ]);

        // Execute the job directly
        $job = new JudgeSubmission($submission);
        $job->handle();

        // Refresh submission state
        $submission->refresh();

        // Assert that the status is updated to a valid enum state
        $this->assertContains($submission->status, ['accepted', 'wrong_answer', 'compilation_error', 'time_limit_exceeded']);

        // Assert that the user received a database notification
        $this->assertCount(1, $contestant->notifications);
        $notificationData = $contestant->notifications->first()->data;
        $this->assertEquals($submission->id, $notificationData['submission_id']);
        $this->assertEquals($problem->title, $notificationData['problem_title']);
        $this->assertEquals($submission->status, $notificationData['status']);
    }

    /**
     * Test index submissions page.
     */
    public function test_submissions_index_route(): void
    {
        $userA = User::factory()->create(['status' => 'approved']);
        $userB = User::factory()->create(['status' => 'approved']);

        $problem = Problem::factory()->create(['created_by' => User::factory()->create()->id]);

        // Submission for User A
        $subA = \App\Models\Submission::create([
            'user_id'      => $userA->id,
            'problem_id'   => $problem->id,
            'code'         => 'print("A")',
            'language'     => 'python',
            'status'       => 'accepted',
            'submitted_at' => now(),
        ]);

        // Submission for User B
        $subB = \App\Models\Submission::create([
            'user_id'      => $userB->id,
            'problem_id'   => $problem->id,
            'code'         => 'print("B")',
            'language'     => 'python',
            'status'       => 'accepted',
            'submitted_at' => now(),
        ]);

        // Guest is redirected
        $this->get(route('submissions.index'))->assertRedirect('/login');

        // User A only sees their own submission
        $response = $this->actingAs($userA)->get(route('submissions.index'));
        $response->assertStatus(200);
        $response->assertSee($problem->title);
        $this->assertCount(1, $response->viewData('submissions'));
        $this->assertEquals($subA->id, $response->viewData('submissions')->first()->id);
    }

    /**
     * Test AJAX polling status endpoint restrictions.
     */
    public function test_submission_status_polling_endpoint(): void
    {
        $userA = User::factory()->create(['status' => 'approved']);
        $userB = User::factory()->create(['status' => 'approved']);
        $problem = Problem::factory()->create(['created_by' => User::factory()->create()->id]);

        $subA = \App\Models\Submission::create([
            'user_id'      => $userA->id,
            'problem_id'   => $problem->id,
            'code'         => 'print("A")',
            'language'     => 'python',
            'status'       => 'pending',
            'submitted_at' => now(),
        ]);

        // User A can access own submission status
        $this->actingAs($userA)
            ->getJson(route('submissions.status', $subA))
            ->assertStatus(200)
            ->assertJson([
                'status' => 'pending',
            ]);

        // User B cannot access User A's submission status
        $this->actingAs($userB)
            ->getJson(route('submissions.status', $subA))
            ->assertStatus(403);
    }
}
