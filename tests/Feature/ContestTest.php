<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test contest creation and relationships.
     */
    public function test_contest_creation_and_relationships(): void
    {
        // 1. Create a contest
        $creator = User::factory()->create();
        $contest = Contest::create([
            'title' => 'Weekly Contest 1',
            'description' => 'A weekly coding challenge.',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(3),
            'is_active' => true,
            'created_by' => $creator->id,
        ]);

        $this->assertDatabaseHas('contests', [
            'title' => 'Weekly Contest 1',
            'is_active' => true,
        ]);

        // 2. Create problems
        $judge = User::factory()->create();
        $problemB = Problem::factory()->create([
            'title' => 'Problem B',
            'slug' => 'problem-b',
            'created_by' => $judge->id,
        ]);
        $problemA = Problem::factory()->create([
            'title' => 'Problem A',
            'slug' => 'problem-a',
            'created_by' => $judge->id,
        ]);

        // Attach with labels (Problem B has label B, Problem A has label A)
        $contest->problems()->attach($problemB->id, ['label' => 'B']);
        $contest->problems()->attach($problemA->id, ['label' => 'A']);

        // Assert problems order by pivot label (Problem A should come first)
        $problems = $contest->problems;
        $this->assertCount(2, $problems);
        $this->assertEquals('Problem A', $problems->first()->title);
        $this->assertEquals('A', $problems->first()->pivot->label);
        $this->assertEquals('Problem B', $problems->last()->title);
        $this->assertEquals('B', $problems->last()->pivot->label);

        // 3. Add participant
        $contestant = User::factory()->create();
        $joinedAt = now()->toDateTimeString();
        $contest->participants()->attach($contestant->id, ['joined_at' => $joinedAt]);

        // Retrieve participant and verify joined_at and aliased pivot name 'participant'
        $participants = $contest->participants;
        $this->assertCount(1, $participants);
        $participant = $participants->first();
        $this->assertEquals($contestant->id, $participant->id);
        $this->assertNotNull($participant->participant);
        $this->assertEquals($joinedAt, $participant->participant->joined_at);
    }
}
