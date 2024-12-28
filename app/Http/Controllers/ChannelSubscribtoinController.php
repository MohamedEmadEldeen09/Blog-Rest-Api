<?php

namespace App\Http\Controllers;

use App\Models\Channel;

class ChannelSubscribtoinController extends Controller
{
    /* subscribe to a channel */
    public function subscribe(Channel $channel)
    {
        $user = request()->user('sanctum');

        $channel->subscribers()->sync([$user->id]);

        return response()->json([
            'message' => 'You have subscribed to the channel' . $channel->name
        ]);
    }

    /* unsubscribe from a channel */    
    public function unsubscribe(Channel $channel)
    {
        $user = request()->user('sanctum');

        $channel->subscribers()->detach($user->id);

        return response()->json([
            'message' => 'You have unsubscribed from the channel ' . $channel->name
        ]);
    }
}
