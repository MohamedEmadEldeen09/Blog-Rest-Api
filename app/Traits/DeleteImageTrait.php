<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

trait DeleteImageTrait
{
    public function deleteImageFrom($image){
        if($image->imageable_type == "user"){
            if(Storage::disk($this->usedDisk)->exists($image->url)){
                $pieces = explode("/", $image->url);
                $targetFolderPathArray = array_slice($pieces,0, count($pieces)-1);
                $targetFolderPath = join("/", $targetFolderPathArray);

                Storage::disk($this->usedDisk)->deleteDirectory($targetFolderPath);
            }
        }

        if($image->imageable_type == "blog"){
            if(Storage::disk($this->usedDisk)->exists($image->url)){
                Storage::disk($this->usedDisk)->delete($image->url);
            }
        }

        $image->delete();
    }
}
