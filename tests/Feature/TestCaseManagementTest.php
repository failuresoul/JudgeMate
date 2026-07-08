<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Problem;
use App\Models\TestCase as ProblemTestCase;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestCaseManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    /**
     * Test that guests/contestants are blocked from managing test cases.
     */
    public function test_guest_cannot_manage_test_cases(): void
    {
        $creator = User::factory()->create();
        $problem = Problem::factory()->create([
            'created_by' => $creator->id
        ]);

        $response = $this->post(route('problems.test-cases.store', $problem), [
            'input' => '1 2',
            'expected_output' => '3',
            'is_hidden' => false,
        ]);

        $response->assertRedirect('/login');
    }

    /**
     * Test that a judge with ProblemSetter role can successfully store and delete test cases.
     */
    public function test_judge_can_add_and_delete_test_cases(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $problem = Problem::factory()->create([
            'created_by' => $judge->id
        ]);

        // 1. Add Test Case
        $response = $this->actingAs($judge)->post(route('problems.test-cases.store', $problem), [
            'input' => '4 5',
            'expected_output' => '9',
            'is_hidden' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('test_cases', [
            'problem_id' => $problem->id,
            'input' => '4 5',
            'expected_output' => '9',
            'is_hidden' => true,
        ]);

        $testCase = $problem->testCases()->first();

        // 2. Delete Test Case
        $response = $this->actingAs($judge)->delete(route('test-cases.destroy', $testCase));
        $response->assertRedirect();
        $this->assertDatabaseMissing('test_cases', ['id' => $testCase->id]);
    }

    /**
     * Test that an Admin is blocked from managing test cases.
     */
    public function test_admin_cannot_manage_test_cases(): void
    {
        $admin = User::factory()->create(['status' => 'approved']);
        $admin->assignRole('Admin');

        $problem = Problem::factory()->create([
            'created_by' => User::factory()->create()->id
        ]);

        // 1. Block Admin Store
        $response = $this->actingAs($admin)->post(route('problems.test-cases.store', $problem), [
            'input' => '1 2',
            'expected_output' => '3',
            'is_hidden' => false,
        ]);
        $response->assertRedirect(route('dashboard'));

        // 2. Block Admin Destroy
        $testCase = \App\Models\TestCase::create([
            'problem_id' => $problem->id,
            'input' => '1 2',
            'expected_output' => '3',
            'is_hidden' => false,
        ]);
        $response = $this->actingAs($admin)->delete(route('test-cases.destroy', $testCase));
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Test that a judge can view their own test cases index page.
     */
    public function test_judge_can_view_test_cases_index(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $response = $this->actingAs($judge)->get(route('judge.test-cases.index'));
        $response->assertStatus(200);
    }

    /**
     * Test that a judge can view the show page for their own test cases.
     */
    public function test_judge_can_view_test_cases_show(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $problem = Problem::factory()->create([
            'created_by' => $judge->id
        ]);

        $response = $this->actingAs($judge)->get(route('judge.test-cases.show', $problem));
        $response->assertStatus(200);
    }

    /**
     * Test that a judge cannot view show page for other judge's problems.
     */
    public function test_judge_cannot_view_other_judge_test_cases_show(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $otherJudge = User::factory()->create();
        $problem = Problem::factory()->create([
            'created_by' => $otherJudge->id
        ]);

        $response = $this->actingAs($judge)->get(route('judge.test-cases.show', $problem));
        $response->assertStatus(403);
    }

    /**
     * Test that an Admin is blocked from editing or deleting problems.
     */
    public function test_admin_cannot_edit_or_delete_problems(): void
    {
        $admin = User::factory()->create(['status' => 'approved']);
        $admin->assignRole('Admin');

        $problem = Problem::factory()->create([
            'created_by' => User::factory()->create()->id
        ]);

        // Edit route block
        $this->actingAs($admin)
            ->get(route('problems.edit', $problem))
            ->assertRedirect(route('dashboard'));

        // Update route block
        $this->actingAs($admin)
            ->put(route('problems.update', $problem), [
                'title' => 'Updated title'
            ])->assertRedirect(route('dashboard'));

        // Destroy route block
        $this->actingAs($admin)
            ->delete(route('problems.destroy', $problem))
            ->assertRedirect(route('dashboard'));
    }
}
