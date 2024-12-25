<?php

namespace App\Traits;

use App\Exceptions\RecordNotFoundMyException;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

trait DeleteImageTrait
{
    public function deleteImageFrom($imageId){
        $image = Image::findOrFail($imageId);

        if(!$image) throw new RecordNotFoundMyException();

        if($image->imageable_type == "user" || $image->imageable_type == "branch"){
            if(Storage::disk($this->usedDisk)->exists($image->url)){
                $pieces = explode("/", $image->url);
                $targetFolderPathArray = array_slice($pieces,0, count($pieces)-1);
                $targetFolderPath = join("/", $targetFolderPathArray);

                Storage::disk($this->usedDisk)->deleteDirectory($targetFolderPath);
            }
        }

        if($image->imageable_type == "item"){
            if(Storage::disk($this->usedDisk)->exists($image->url)){
                Storage::disk($this->usedDisk)->delete($image->url);
            }
        }

        $image->delete();
    }
}
