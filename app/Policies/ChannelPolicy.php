<?php

namespace App\Policies;

use App\Exceptions\UnAuthorizedToMakeActionMyException;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChannelPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Channel $channel) : Response 
    {
        return $user->id === $channel->user_id
            ? Response::allow()
            : throw new UnAuthorizedToMakeActionMyException('You are not authorized to update this channel');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Channel $channel): Response
    {
        return $user->id === $channel->user_id 
            ? Response::allow() 
            : throw new UnAuthorizedToMakeActionMyException('You are not authorized to delete this channel');  
    }

}
