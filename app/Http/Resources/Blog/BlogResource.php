<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\Channel\ChannelResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Image\ImageResource;
use App\Http\Resources\Like\LikeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->format('Y-m-d'),
            'user' => $this->whenLoaded('author', $this->author->name),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'channel' => new ChannelResource($this->whenLoaded('channel'))
        ];
    }
}
