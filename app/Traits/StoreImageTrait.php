<?php

namespace App\Traits;

use App\Exceptions\InternalServerErrorMyException;
use App\Exceptions\RecordNotFoundMyException;
use App\Models\Blog;
use App\Models\User;

trait StoreImageTrait
{
    public function storeImageFor($data, $disk){
        if($data['imageable_type'] === "user"){
            return $this->storeImageForUser($data, $disk);
        }

        if($data['imageable_type'] === "item"){
            return $this->storeImageForBlog($data, $disk);
        }
    }

    private function storeImageForUser($data, $disk){
        $user = User::find($data['imageable_id']);

        if(!$user) throw new RecordNotFoundMyException();

        try {
            $image = $data['image'];

            $userProfileAvatarName = "profile.".$image->getClientOriginalExtension();
            $folderPath = 'user/'.$data['imageable_id'];

            //store in the storage
            $path = $image->storeAs($folderPath, $userProfileAvatarName, $disk);

            //store in the database
            if($user->image){
                $user->image()->delete();
            }

            $image = $user->image()->create([
                "url" => $path,
            ]);   
    
        } catch (\Throwable $th) {
            throw new InternalServerErrorMyException($th->getMessage());
        }

        return $image;
    }

    private function storeImageForBlog($data, $disk){
        $item = Blog::find($data['imageable_id']);

        if(!$item) throw new RecordNotFoundMyException();

        try {
            $image = $data['image'];

            $folderPath = 'blog/'.$data['imageable_id'];

            //store in the storage
            $path = $image->store($folderPath, $disk);

            //store in the database
            $image = $item->images()->create([
                "url" => $path,
            ]);   
            
        } catch (\Throwable $th) {
            throw new InternalServerErrorMyException($th->getMessage());
        }

        return $image;
    }
}
