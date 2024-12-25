<?php

use App\Enums\BlogActionsEnum;
use App\Http\Controllers\Blog\BlogController;
use App\Models\Blog;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    $channel = Channel::findOrFail(291)->first();
    return $channel->subscribers;
});

Route::get('/user/{user}/channels', function (User $user) {
    return $user->suscribedChannels;
});

Route::get('/channel/{channel}/users', function (Channel $channel) {
    $channel->subscribers()->detach([50]);
    return $channel->subscribers;
});

//blog
//Route::apiResource('blog', BlogController::class);


//seed the db -->> done
//blog crud + search + filter
//image crud
//customize the authentiction 
//authorization for the blog -->> policy