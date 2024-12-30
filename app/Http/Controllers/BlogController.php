<?php

namespace App\Http\Controllers;

use App\Events\NewBlogPublished;
use App\Events\NewBlogPublishedEvent;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Http\Requests\Image\StoreImageRequest;
use App\Http\Resources\Blog\BlogResource;
use App\Models\Channel;
use App\Models\Image;
use App\Traits\DeleteImageTrait;
use App\Traits\StoreImageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class BlogController extends Controller implements HasMiddleware
{
    use StoreImageTrait, DeleteImageTrait;

    protected $usedDisk = 'public';

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Channel $channel)
    {    
        /* check if the user is authorized to view all the channel blogs */
        Gate::authorize('viewAny', [Blog::class, $channel]);    
        //request()->user('sanctum')->can('viewAny', [Blog::class, $channel]);

        $catagory_name = request()->query('catagory_name');
        $blog_search = request()->query('blog_search');

        /* if there is no query search parameters */
        if(! $catagory_name && ! $blog_search){
            $blogs = BlogResource::collection($channel->blogs()->paginate(6));
            return response()->json([
                'blogs' => $blogs
            ], 200);
        }

        /* if search parameters exist */
        $blogs = $channel->blogs()
                ->whereHas('catagory', function (Builder $query) use($catagory_name) {
                    $query->where('name', 'like', "%" . $catagory_name . "%");
                })
                ->filter(['blog_search' => $blog_search])
                ->paginate(4)
                ->withQueryString();
        
        return response()->json([
            'blogs' => BlogResource::collection($blogs),
            'filters' => [
                'blog_search' =>  $blog_search,
                'catagory_name' => $catagory_name,
            ] 
        ], 200);
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
         /* save the blog images */
        // foreach ($validated['images'] as $image) {
        //     $this->uplaodBlogImage($blog, $image);
        // }

        /* send new blog published emails */
        NewBlogPublishedEvent::dispatch($blog);

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
        //request()->user('sanctum')->can('view', [Blog::class, $blog, $channel]);

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

        $blog->update($validated);
        
        /* save the blog images */
        
        return response()->json([
            'message' => 'Blog updated successfully.',
            'blog' => new BlogResource($blog)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Channel $channel, Blog $blog)
    {
        /* check if the user is authorized to delete the blog */
        Gate::authorize('delete', $blog);
        //request()->user('sanctum')->can('delete', $blog);

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully.'], 200);
    }

    /* upload blog image*/
    public function uplaodBlogImage($blog, $image)
    {
        $data['imageable_type'] = 'blog';
        $data['blog'] = $blog;
        $data['image'] = $image;
        $this->storeImageFor($data, $this->usedDisk);
    }

    /* change blog image*/
    public function changeBlogImage(StoreImageRequest $request, Channel $channel, Blog $blog, Image $image)
    {
        $this->deleteImageFrom($image);
        $this->uplaodBlogImage($blog, $image);
    }

    /* delete blog image*/
    public function deleteBlogImage(Image $image)
    {
        $this->deleteImageFrom($image);
        return response(null, 204); 
    }
}
