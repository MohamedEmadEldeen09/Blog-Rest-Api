<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ImageController;
use App\Models\Blog;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/test', function () {
//     $channel = Channel::findOrFail(291)->first();
//     return $channel->subscribers;
// });

// Route::get('/user/{user}/channels', function (User $user) {
//     return $user->suscribedChannels;
// });

// Route::get('/channel/{channel}/users', function (Channel $channel) {
//     $channel->subscribers()->detach([50]);
//     return $channel->subscribers;
// });

//blog
//Route::apiResource('blog', BlogController::class);

//image
//Route::apiResource('image', ImageController::class)->middleware(['throttle:50,1']);

// Route::controller(ImageController::class)->middleware(['throttle:50,1'])
//     ->group(function () {
//         Route::get('/image/{id}', 'show')->name('image.show');
//         Route::post('/image', 'store')->name('image.store');
//         Route::put('/image/{id}', 'update')->name('image.update');
//         Route::delete('/image/{id}', 'destroy')->name('image.destroy');
//     }
// );

//seed the db -->> done
//customize the authentiction --> done
//handle erros -->> done
//image crud -->> done

//blog crud + search + filter
//channel crud
//authorization for the blog using policy
//testig endPoints
