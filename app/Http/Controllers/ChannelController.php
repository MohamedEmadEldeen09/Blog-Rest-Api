<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalServerErrorMyException;
use App\Exceptions\RecordNotFoundMyException;
use App\Http\Requests\Channel\StoreChannelRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Resources\Channel\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    public function index()
    {
        try {
            $allChannels = ChannelResource::collection(Channel::paginate(6));
            return response()->json($allChannels, 200);

        } catch (\Throwable $th) {
            /* in case of unexcepected error happened */
            throw new InternalServerErrorMyException('sonething went wrong!, please try again later');
        }
    }

    public function store(StoreChannelRequest $request)
    {
        try {
            /* get the validated data */
            $valdated = $request->validated();

            /* save to db */
            $channel = Channel::create([
                'name' => $valdated['name'],
                'user_id' => request()->user()->id
            ]);

            /* return response */
            return response()->json(new ChannelResource($channel), 201);

        } catch (\Throwable $th) {
            /* in case of unexcepected error happened */
            throw new InternalServerErrorMyException('sonething went wrong!, please try again later');
        }
    }

    public function show(Channel $channel)
    {
        return new ChannelResource($channel);
    }

    public function update(UpdateChannelRequest $request, Channel $channel)
    {  
        try {
            /* get the validated data */
            $valdated = $request->validated();

            /* save to db */
            $channel->update([
                'name' => $valdated['name'],
            ]);

            /* return response */
            return response()->json(new ChannelResource($channel), 201);

        } catch (\Throwable $th) {
            throw new InternalServerErrorMyException('sonething went wrong!, please try again later');
        }
    }

    public function destroy(Channel $channel)
    {
        /* authorization is this user can delete this channel */    
        Gate::authorize('delete', $channel);

        try {
            /* delete the channel */
            $channel->delete();

            /* return response */
            return response()->json(null, 204);

        } catch (\Throwable $th) {
            /* in case of unexcepected error happened */
            throw new InternalServerErrorMyException('sonething went wrong!, please try again later');
        }
    }
}
