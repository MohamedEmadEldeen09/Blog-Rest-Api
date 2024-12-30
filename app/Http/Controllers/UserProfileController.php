<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\StoreImageRequest;
use App\Http\Resources\Image\ImageResource;
use App\Http\Resources\User\UserResource;
use App\Models\Image;
use App\Traits\DeleteImageTrait;
use App\Traits\StoreImageTrait;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    use StoreImageTrait, DeleteImageTrait;

    protected $usedDisk = 'public';

    /* get user profile info*/
    public function profile () {
        return new UserResource(request()->user('sanctum')->with('image')->first());
    }

    /* upload user profile image*/
    public function uplaodProfileImage(StoreImageRequest $request)
    {
        $validatedData = $request->validated();
        //dd($validatedData);
        $validatedData['imageable_type'] = 'user';

        $image = $this->storeImageFor($validatedData, $this->usedDisk);
        
        return response([
            "image" => new ImageResource($image) 
        ], 201);
    }

    /* change user profile image*/
    public function changeProfileImage(StoreImageRequest $request, Image $image)
    {
        $this->deleteImageFrom($image);
        $this->uplaodProfileImage($request);
    }

    /* delete user profile image*/
    public function deleteProfileImage(Image $image)
    {
        $this->deleteImageFrom($image);
        return response(null, 204); 
    }
}
