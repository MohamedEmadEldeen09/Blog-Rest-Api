<?php

namespace App\Listeners;

use App\Models\Channel;
use App\Notifications\SendBlogEmailsNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendBlogEmailsListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $blog = $event->blog;

        $channel = Channel::where('id', $blog->channel_id)->first();

        $toSubscribers= $channel->subscribers()->where('user_id', '!=', $blog->user_id)->get();

        Notification::send($toSubscribers, new SendBlogEmailsNotification($blog));
    }
}
