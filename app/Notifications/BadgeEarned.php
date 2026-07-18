<?php

namespace App\Notifications;

use App\Models\Badge;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BadgeEarned extends Notification
{
    use Queueable;

    public $badge;

    public function __construct(Badge $badge)
    {
        $this->badge = $badge;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'badge',
            'title' => 'Badge Earned!',
            'message' => "You unlocked the {$this->badge->name} badge.",
            'icon' => '🏆',
            'url' => route('dashboard')
        ];
    }
}
