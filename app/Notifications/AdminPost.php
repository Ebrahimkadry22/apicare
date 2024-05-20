<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminPost extends Notification
{
    use Queueable;
    protected $post , $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user,$post)
    {
        $this->user =$user;
        $this->post =$post;
    }

    /**
     * Get the notification's delivery channels.
     *
    //  * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'post' => $this->post,
            'user' => $this->user,
        ];
    }
}
