<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateContestStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_artisan_command_updates_contest_statuses_correctly(): void
    {
        $creator = User::factory()->create();

        // Contest 1: starts_at in the past, ends_at in the future, is_active false => should become active (true)
        $contestToActivate = Contest::create([
            'title' => 'Contest to Activate',
            'description' => 'Should be activated',
            'starts_at' => Carbon::now()->subHour(),
            'ends_at' => Carbon::now()->addHours(2),
            'is_active' => false,
            'created_by' => $creator->id,
        ]);

        // Contest 2: starts_at in the past, ends_at in the past, is_active true => should become inactive (false)
        $contestToDeactivate = Contest::create([
            'title' => 'Contest to Deactivate',
            'description' => 'Should be deactivated',
            'starts_at' => Carbon::now()->subHours(3),
            'ends_at' => Carbon::now()->subHour(),
            'is_active' => true,
            'created_by' => $creator->id,
        ]);

        // Contest 3: starts_at in the future, ends_at in the future, is_active false => should remain inactive (false)
        $contestFuture = Contest::create([
            'title' => 'Future Contest',
            'description' => 'Should remain inactive',
            'starts_at' => Carbon::now()->addHour(),
            'ends_at' => Carbon::now()->addHours(3),
            'is_active' => false,
            'created_by' => $creator->id,
        ]);

        // Contest 4: starts_at in the past, ends_at in the future, is_active true => should remain active (true)
        $contestActiveRemain = Contest::create([
            'title' => 'Already Active Contest',
            'description' => 'Should remain active',
            'starts_at' => Carbon::now()->subHour(),
            'ends_at' => Carbon::now()->addHour(),
            'is_active' => true,
            'created_by' => $creator->id,
        ]);

        // Contest 5: starts_at in the past, ends_at in the past, is_active false => should remain inactive (false)
        $contestEndedInactive = Contest::create([
            'title' => 'Ended Inactive Contest',
            'description' => 'Should remain inactive',
            'starts_at' => Carbon::now()->subHours(4),
            'ends_at' => Carbon::now()->subHours(2),
            'is_active' => false,
            'created_by' => $creator->id,
        ]);

        // Run the command
        $this->artisan('contest:update-status')
            ->expectsOutput("Activated contest: Contest to Activate (ID: {$contestToActivate->id})")
            ->expectsOutput("Deactivated contest: Contest to Deactivate (ID: {$contestToDeactivate->id})")
            ->expectsOutput('Contest statuses updated successfully.')
            ->assertExitCode(0);

        // Assert database values
        $this->assertTrue($contestToActivate->refresh()->is_active);
        $this->assertFalse($contestToDeactivate->refresh()->is_active);
        $this->assertFalse($contestFuture->refresh()->is_active);
        $this->assertTrue($contestActiveRemain->refresh()->is_active);
        $this->assertFalse($contestEndedInactive->refresh()->is_active);
    }
}
