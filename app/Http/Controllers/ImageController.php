<?php

namespace App\Http\Controllers;

use App\Exceptions\RecordNotFoundMyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Image\StoreImageRequest;
use App\Http\Resources\Image\ImageResource;
use App\Models\Image;
use App\Traits\DeleteImageTrait;
use App\Traits\StoreImageTrait;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /*
    use StoreImageTrait, DeleteImageTrait;

    protected $usedDisk = 'public';

    public function store(StoreImageRequest $request)
    {
        $validatedData = $request->validated();
        
        $image = $this->storeImageFor($validatedData, $this->usedDisk);

        return response([
            "image" => new ImageResource($image) 
        ], 201);
    }

    public function show(string $id)
    {
        $image = Image::findOrFail($id);

        return new ImageResource($image);
    }

    public function update(StoreImageRequest $request, string $id)
    {
        $this->deleteImageFrom($id);
        return $this->store($request);
    }

    public function destroy(string $id)
    {        
        $this->deleteImageFrom($id);
        return response(null, 204); 
    }
    */
}
