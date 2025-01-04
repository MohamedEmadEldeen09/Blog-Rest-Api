<?php

use Illuminate\Support\Facades\Route;

/* authentication routes */
require __DIR__.'/auth.php';

/* all the blog app routes*/
require __DIR__.'/main.php';

/* the admin routes */
require __DIR__.'/admin.php';

/* in case if a rout is not found */
Route::fallback(function(){
    return response()->json([
            'message' => 'Route Not Found.'
        ], 404);
    }
);