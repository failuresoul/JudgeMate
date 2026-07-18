<?php

namespace App\Notifications;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BlogApproved extends Notification
{
    use Queueable;

    public $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'blog',
            'title' => 'New Inspiration',
            'message' => "New post: {$this->blog->title}",
            'icon' => '📝',
            'url' => route('blogs.show', $this->blog)
        ];
    }
}
