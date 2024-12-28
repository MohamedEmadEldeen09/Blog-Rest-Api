<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Http\Resources\Blog\BlogResource;
use App\Models\Channel;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;


class BlogController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', only: ['store', 'update', 'destroy'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Channel $channel)
    {    
        /* check if the user is authorized to view all the channel blogs */
        Gate::authorize('viewAny', [Blog::class, $channel]);    

        return BlogResource::collection($channel->blogs()->paginate(6));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request, Channel $channel)
    {
        $validated = $request->validated();

        $blog = Blog::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'catagory_id' => $validated['catagory_id'],
            'channel_id' => $channel->id,
            'user_id' => $request->user('sanctum')->id
        ]);

        /* save the blog images */
        //$blog->images()->createMany($validated['images']);

        return response()->json([
            'message' => 'Blog created successfully.',
            'blog' => new BlogResource($blog)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel, Blog $blog)
    {
        /* check if the user is authorized to view the blog */
        Gate::authorize('view', [Blog::class, $blog, $channel]);

        return new BlogResource(
            $blog->with(['author', 'images', 'likes', 'comments'])->find($blog->id)
        );   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Channel $channel, Blog $blog)
    {
        $validated = $request->validated();

        if($validated){
            $blog->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'catagory_id' => $validated['catagory_id'],
            ]);
        }
        
        /* save the blog images */
        //$blog->images()->createMany($validated['images']);

        return response()->json([
            'message' => 'Blog updated successfully.',
            'blog' => new BlogResource($blog)
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Channel $channel, Blog $blog)
    {
        /* check if the user is authorized to delete the blog */
        Gate::authorize('delete', $blog);

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully.'], 200);
    }
}
