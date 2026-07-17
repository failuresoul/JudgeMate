<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoreboardPdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    /**
     * Test that guest users cannot download the scoreboard PDF.
     */
    public function test_guest_cannot_download_scoreboard_pdf(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $contest = Contest::create([
            'title' => 'Test Contest',
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
            'is_active' => true,
            'is_approved' => true,
            'created_by' => $judge->id,
        ]);

        $response = $this->get(route('contests.scoreboard.pdf', $contest));
        $response->assertRedirect('/login');
    }

    /**
     * Test that contestant users can download the scoreboard PDF.
     */
    public function test_contestant_can_download_scoreboard_pdf(): void
    {
        $judge = User::factory()->create(['status' => 'approved']);
        $judge->assignRole('ProblemSetter');

        $contest = Contest::create([
            'title' => 'Test Contest',
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
            'is_active' => true,
            'is_approved' => true,
            'created_by' => $judge->id,
        ]);

        $contestant = User::factory()->create(['status' => 'approved']);
        $contestant->assignRole('Contestant');

        $response = $this->actingAs($contestant)->get(route('contests.scoreboard.pdf', $contest));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename=scoreboard-' . $contest->id . '.pdf');
        
        $content = $response->getContent();
        $this->assertStringStartsWith('%PDF-', $content);
    }

    /**
     * Test that admins can successfully download the scoreboard PDF.
     */
    public function test_admin_can_download_scoreboard_pdf(): void
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

        $contest = Contest::create([
            'title' => 'Scoreboard Contest',
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
            'is_active' => true,
            'is_approved' => true,
            'created_by' => $judge->id,
        ]);

        $contest->problems()->attach($problemA->id, ['label' => 'A']);

        // Admin user
        $admin = User::factory()->create(['status' => 'approved', 'name' => 'Admin User']);
        $admin->assignRole('Admin');

        // Contestant Alice
        $alice = User::factory()->create(['status' => 'approved', 'name' => 'Alice']);
        $alice->assignRole('Contestant');

        $contest->participants()->attach($alice->id, ['joined_at' => now()]);

        // Alice accepted submission
        Submission::create([
            'user_id' => $alice->id,
            'problem_id' => $problemA->id,
            'contest_id' => $contest->id,
            'language' => 'cpp',
            'code' => 'xxx',
            'status' => 'accepted',
            'submitted_at' => $contest->starts_at->copy()->addMinutes(10),
        ]);

        $response = $this->actingAs($admin)->get(route('contests.scoreboard.pdf', $contest));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename=scoreboard-' . $contest->id . '.pdf');
        
        $content = $response->getContent();
        // Since dompdf generates a valid PDF file, the content starts with %PDF-
        $this->assertStringStartsWith('%PDF-', $content);
    }
}
