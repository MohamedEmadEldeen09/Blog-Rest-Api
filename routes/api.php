<?php

use App\Http\Controllers\Blog\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

/* all the blog app routes*/
require __DIR__.'/blog.php';
