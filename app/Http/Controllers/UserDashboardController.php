<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\BlogResource;
use App\Http\Resources\Channel\ChannelResource;

class UserDashboardController extends Controller
{
    /* my own channels -->> the channels that i created */
    public function myOwnChannels () {
        $user = request()->user('sanctum');

        $ownChannels =  ChannelResource::collection($user->ownChannels()->paginate()); 
        
        return response()->json($ownChannels, 200);
    }

    /* my sucbcribed channels -->> the channels that i am a subsciber to it*/
    public function mySusbcribedChannels () {
        $user = request()->user('sanctum');

        $subscribedChannels = ChannelResource::collection($user->suscribedChannels()->paginate());
            
        return response()->json($subscribedChannels, 200);
    }

    /* my blogs */
    public function myBlogs () {
        $user = request()->user('sanctum');

        return response()->json(
                BlogResource::collection(
                    $user->blogs()
                    ->with(['author', 'images', 'likes', 'comments', 'channel'])
                    ->paginate(4))
            , 200);
    }
}
