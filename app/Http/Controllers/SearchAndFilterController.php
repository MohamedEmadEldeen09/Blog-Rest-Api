<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\BlogResource;
use App\Models\Blog;
use App\Models\Channel;
use Illuminate\Http\Request;

class SearchAndFilterController extends Controller
{
    /* trending blogs -->> the biggest number of likes */
    public function trendingBlogs (Channel $channel) {
        // Get the blog with the biggest number of likes
        $trendingBlog = $channel->blogs()
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->first();

        if ($trendingBlog) {
            return response()->json([
                'blog' =>new BlogResource($trendingBlog)
            ], 200);
        } else {
            return response()->json([
                'message' => 'No blogs found'
            ], 404);
        }
    }
}
