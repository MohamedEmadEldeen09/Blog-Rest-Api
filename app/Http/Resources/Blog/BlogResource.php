<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\Catagory\CatagoryResource;
use App\Http\Resources\Channel\ChannelResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Image\ImageResource;
use App\Http\Resources\Like\LikeResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* overiew routes */
        $isPreviewAllBlogs = Route::currentRouteName() === "channel.blog.index";
        $isPreviewTrendingBlogs = Route::currentRouteName() === "channel.blog.trending";
        $isPreviewDashboardBlogs = Route::currentRouteName() === "dashboard.blogs";

        if($isPreviewAllBlogs || $isPreviewTrendingBlogs || $isPreviewDashboardBlogs){
            return $this->blogPreviewResource();
        }

        /* details routes */
        // $isShowDetailedBlog = Route::currentRouteName() === "channel.blog.show";
        // if($isShowDetailedBlog){
        //     return $this->blogDetailsResource();
        // }

        /* details routes */
        return $this->blogDetailsResource();
    }

    /* when show the blog details -->> route = channel.blog.show */
    public function blogDetailsResource () {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'content' => $this->content,

            'created_at' => $this->created_at->format('Y-m-d'),

            'author' => new UserResource($this->author),

            'catagory' => new CatagoryResource($this->catagory),

            'images' => ImageResource::collection($this->images),

            'likes_count' => $this->likes()->count(),

            'likes' => LikeResource::collection($this->likes),

            'comments' => CommentResource::collection($this->comments),

            'channel' => new ChannelResource($this->channel)
        ];
    }

    /**
     * when show the overview about each blog 
     *  -->> route = channel.blog.index
     *  -->> route = blohs.trending 
     **/
    public function blogPreviewResource () {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'created_at' => $this->created_at->format('Y-m-d'),

            'user' => $this->whenLoaded('author', $this->author->name),

            'catagory' => new CatagoryResource($this->catagory),

            'preview_image' => new ImageResource ($this->images()->first()),

            'likes_count' => $this->likes()->count(),

            'comments' => $this->comments()->count(),

            'channel' => new ChannelResource($this->whenLoaded('channel'))
        ];
    }
}
