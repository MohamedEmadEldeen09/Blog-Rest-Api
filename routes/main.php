<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ChannelSubscribtoinController;
use App\Http\Controllers\SearchAndFilterController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Resources\User\UserResource;
use App\Models\Blog;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function (Channel $channel) {
    return response(['message' => 'welcome '. request()->user('sanctum')->name]);
})->middleware(['auth:user']);

/* user profile */
Route::controller(UserProfileController::class)
    ->prefix('/user/profile')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', 'profile')->name('profile.profile');
        Route::post('/image', 'uplaodProfileImage')->name('profile.image-upload');
        Route::post('/image/{image}', 'changeProfileImage')->name('profile.image-change');
        Route::post('/image/{image}', 'deleteProfileImage')->name('profile.image-delete');
    });


/* user dashboard */
Route::controller(UserDashboardController::class)
    ->prefix('/user/dashboard')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/own-channels', 'myOwnChannels')->name('dashboard.own-channels');
        Route::get('/subscribed-channels', 'mySusbcribedChannels')->name('dashboard.subscribed-channels');
        Route::get('/blogs', 'myBlogs')->name('dashboard.blogs');
    });


/* channel */
Route::apiResource('channel', ChannelController::class);


/* channel subscribtion */
Route::controller(ChannelSubscribtoinController::class)
    ->prefix('channel')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::post('/{channel}/subscribe', 'subscribe')
            ->middleware('can:subscribe,channel')->name('channel.subscribe');
        Route::post('/{channel}/unsubscribe', 'unsubscribe')
            ->middleware('can:unsubscribe,channel')->name('channel.unsubscribe');
    });


/* blog */
Route::scopeBindings()->group(function () {
        Route::apiResource('channel.blog', BlogController::class);
    });


/* search and filter */
Route::controller(SearchAndFilterController::class)
    ->prefix('search')
    ->group(function () {
        Route::get('/channel/{channel}/blog/trending', 'trendingBlogs')
            ->name('channel.blog.trending');
    });



//seed the db -->> done
//customize the authentiction --> done
//handle erros -->> done
//channel crud -->> done
//authorization using policy -->> done
//channel subscribtion -->> done
//user dashboard -->> done
//blog crud -->> done
//search + filter -->> done
//admin layer with abilities muti auth -->> done


//image crud 
//blog actions -->> report a blog to the admin as a notificatoin trigered by an event
//send notificatoin the channel subscribers throght email when new blog created 
//testig endPoints
//solve pagination problem that it is not showing in the resource response

