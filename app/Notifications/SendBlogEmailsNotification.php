<?php

namespace App\Notifications;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendBlogEmailsNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    private Blog $blog;

    /**
     * Create a new notification instance.
     */
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line("New blog has been published.")

                    ->action('Read About It', 
                        route('channel.blog.show', [
                                'channel' => $this->blog->channel_id,
                                'blog' => $this->blog->id
                            ]))

                    ->line('Thank you for using our Blog application!');
    }
}
