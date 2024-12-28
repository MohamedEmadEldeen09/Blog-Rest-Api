<?php

use App\Http\Controllers\Admin\MainAppChannelAdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/admin', fn () => response('hello admin'))
        ->middleware(['auth:sanctum', 'is-admin']);

Route::middleware(['auth:sanctum', 'is-admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', fn () => response('hello admin'));

        Route::controller(MainAppChannelAdminController::class)
        ->prefix('channel')
        ->group(function () {
            Route::get('/{channel}/subscribers', 'channelSubscribers')
                ->name('admin.channel.subscribers');
        });
    }
);

