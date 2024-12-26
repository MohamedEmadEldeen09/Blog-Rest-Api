<?php

use App\Http\Controllers\Blog\BlogController;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

/* all the blog app routes*/
require __DIR__.'/blog.php';

/* authentication routes */
require __DIR__.'/auth.php';

Route::fallback(function(){
    return response()->json([
            'message' => 'Route Not Found. If error persists, contact'
        ], 404);
    }
);