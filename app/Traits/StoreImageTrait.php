<?php

namespace App\Traits;

use App\Exceptions\InternalServerErrorMyException;
use App\Models\Blog;
use App\Models\User;

trait StoreImageTrait
{
    public function storeImageFor($data, $disk){
        if($data['imageable_type'] === "user"){
            return $this->storeImageForUser($data, $disk);
        }

        if($data['imageable_type'] === "blog"){
            return $this->storeImageForBlog($data, $disk);
        }
    }

    private function storeImageForUser($data, $disk){
        try {
            $user = request()->user('sanctum');

            $image = $data['image'];

            $userProfileAvatarName = "profile.".$image->getClientOriginalExtension();
            $folderPath = 'user/' . $user->id;

            /* store in the storage */
            $path = $image->storeAs($folderPath, $userProfileAvatarName, $disk);

            /**
             * first check if the user has an image record 
             * in the datavase then delete that rescord in order 
             * to just have one image rescord 
             */
            if($user->image){
                $user->image()->delete();
            }

            /* store in the database */
            $image = $user->image()->create([
                "url" => $path,
            ]);   
    
            return $image;
        } catch (\Throwable $th) {
            throw new InternalServerErrorMyException($th->getMessage());
        }
    }

    private function storeImageForBlog($data, $disk){
        $item = Blog::findOrFail($data['imageable_id']);

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
