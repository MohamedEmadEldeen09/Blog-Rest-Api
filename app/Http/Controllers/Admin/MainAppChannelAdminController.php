<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class MainAppChannelAdminController extends Controller
{
    public function channelSubscribers (Channel $channel) {
        return response($channel->subscribers(), 200);
    }
}
