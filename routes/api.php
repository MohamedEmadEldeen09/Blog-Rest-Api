<?php

use App\Http\Controllers\Blog\BlogController;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

/* authentication routes */
require __DIR__.'/auth.php';

/* all the blog app routes*/
require __DIR__.'/blog-app.php';

/* in case if a rout not fount */
Route::fallback(function(){
    return response()->json([
            'message' => 'Route Not Found. If error persists, contact'
        ], 404);
    }
);