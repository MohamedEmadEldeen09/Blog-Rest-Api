<?php

use App\Http\Controllers\Admin\MainAppActionsController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/* admin login */
Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest', 'is-unauth', 'throttle:6,1'])
    ->name('admin.login');

/* main functionality */
Route::middleware('auth:admin-api')->prefix('admin')->group(function () {
        /* welcome route to test */
        Route::get('/', fn () => response('Hello '. request()->user('sanctum')->name));

        /* admin logout */
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('admin.logout');

        /* */
        Route::controller(MainAppActionsController::class)->prefix('channel')
            ->group(function () {
                
            });
    }
);
