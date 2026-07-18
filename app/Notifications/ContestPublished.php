<?php

namespace App\Notifications;

use App\Models\Contest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContestPublished extends Notification
{
    use Queueable;

    public $contest;

    public function __construct(Contest $contest)
    {
        $this->contest = $contest;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'contest',
            'title' => 'New Contest',
            'message' => "{$this->contest->title} has been published.",
            'icon' => '🚀',
            'url' => route('contests.show', $this->contest)
        ];
    }
}
