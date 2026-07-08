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
}
