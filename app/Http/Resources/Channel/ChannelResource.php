<?php

namespace App\Http\Resources\Channel;

use App\Http\Resources\Blog\BlogResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
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
            'name' => $this->name,
            'created_at' =>$this->created_at->format('Y-m-d'),
            'blogs' => BlogResource::collection($this->whenLoaded('blogs')),
        ];
    }
}
