<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalServerErrorMyException;
use App\Http\Requests\Channel\StoreChannelRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Resources\Channel\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class ChannelController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    public function index(Request $request)
    {
        $channel_name = $request->query('channel_name');

        /* if there is no query search parameters */
        if(! $channel_name){
            $channels = ChannelResource::collection(Channel::paginate(4));
            return response()->json([
                'channels' =>$channels
            ], 200);
        }
        
         /* if search parameters exist */
        $channels = ChannelResource::collection(
            Channel::filter(request(['channel_name']))
                ->paginate(4)->withQueryString());
        
        return response()->json([
            'channels' => $channels,
            'filters' => [
                'channel_name' => $request->query('channel_name'),
            ] 
        ], 200);
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
        /* get the validated data */
        $valdated = $request->validated();

        /* save to db */
        $channel->update([
            'name' => $valdated['name'],
        ]);

        /* return response */
        return response()->json(new ChannelResource($channel), 201);
    }

    public function destroy(Channel $channel)
    {
        /* authorization is this user can delete this channel */    
        //Gate::authorize('delete', $channel);
        request()->user('sanctum')->can('delete', $channel);

        /* delete the channel */
        $channel->delete();

        /* return response */
        return response()->json(null, 204);
    }
}
