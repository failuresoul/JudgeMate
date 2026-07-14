<?php

namespace Tests\Feature;

use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use App\Notifications\VerdictNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that guests cannot access notifications endpoints.
     */
    public function test_guests_cannot_access_notification_endpoints(): void
    {
        $this->getJson(route('notifications.unread-count'))
            ->assertStatus(401);

        $this->postJson(route('notifications.mark-read'))
            ->assertStatus(401);
    }

    /**
     * Test that authenticated users can fetch their unread count.
     */
    public function test_authenticated_user_can_get_unread_count(): void
    {
        $user = User::factory()->create();
        $problem = Problem::factory()->create();

        $submission = Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problem->id,
            'code' => 'print(1)',
            'language' => 'python',
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);

        $this->actingAs($user)
            ->getJson(route('notifications.unread-count'))
            ->assertStatus(200)
            ->assertJson(['unread_count' => 0]);

        // Send a notification
        $user->notify(new VerdictNotification($submission));

        $this->actingAs($user)
            ->getJson(route('notifications.unread-count'))
            ->assertStatus(200)
            ->assertJson(['unread_count' => 1]);
    }

    /**
     * Test that authenticated users can mark notifications as read.
     */
    public function test_authenticated_user_can_mark_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $problem = Problem::factory()->create();

        $submission = Submission::create([
            'user_id' => $user->id,
            'problem_id' => $problem->id,
            'code' => 'print(1)',
            'language' => 'python',
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);

        $user->notify(new VerdictNotification($submission));

        $this->assertEquals(1, $user->unreadNotifications()->count());

        $this->actingAs($user)
            ->postJson(route('notifications.mark-read'))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(0, $user->unreadNotifications()->count());
    }
}
