<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ChannelSubscribtoinController;
use App\Http\Controllers\SearchAndFilterController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

/* user profile */
Route::controller(UserProfileController::class)
    ->prefix('/user/profile')
    ->middleware(['auth:user']) //only the uer can update his profile the admin not allowed
    ->group(function () {
        Route::get('/', 'profile')->name('profile.profile');
        Route::post('/image', 'uplaodProfileImage')->name('profile.image-upload');
        Route::put('/image/{image}', 'changeProfileImage')->name('profile.image-change');
        Route::delete('/image/{image}', 'deleteProfileImage')->name('profile.image-delete');
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
        /* main functionality of the CRUD operations */
        Route::apiResource('channel.blog', BlogController::class);
        
        /* blog image */
        Route::put('/channel/{channel}/blog/{blog}/image/{image}', 
            [BlogController::class, 'updateBlogImage'])->name('channel.blog.image-update');

        Route::delete('/channel/{channel}/blog/{blog}/image/{image}', 
            [BlogController::class, 'deleteBlogImage'])->name('channel.blog.image-delete');
    });


/* search and filter */
Route::controller(SearchAndFilterController::class)
    ->prefix('search')
    ->group(function () {
        Route::get('/channel/{channel}/blog/trending', 'trendingBlogs')
            ->name('channel.blog.trending');
    });


