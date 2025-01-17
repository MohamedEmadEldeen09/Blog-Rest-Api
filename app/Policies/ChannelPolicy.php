<?php

namespace App\Policies;

use App\Enums\AppConstantsEnum;
use App\Exceptions\UnAuthorizedToMakeActionMyException;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class ChannelPolicy
{
    /* check if the user type is admin. */
    public function before(?Model $model, $ability)
    {
        /**
         * if the user is admin he can do anything except 
         * the ability of updating a channel
         */
        if($model && $model->isAdmin()){
            if($ability == 'update'){
                throw throw new UnAuthorizedToMakeActionMyException('Even if you are an admin you can not update the user channel.');
            }

            return true;
        }

        return null;
    }

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

    /* check if the auth user can subcribe this channel */
    public function subscribe (User $user, Channel $channel) {
        $isUserTheOwner = $user->id === $channel->user_id;

        $isUserASubsciber = $channel->subscribers()->where('user_id', $user->id)->exists();

        if($isUserTheOwner){
            throw new UnAuthorizedToMakeActionMyException('You are the owner of this channel.');
        }

        if($isUserASubsciber){
            throw new UnAuthorizedToMakeActionMyException('You are a subscriber in this channel.');
        }

        return true;
    }

     /* check if the auth user can unsubcribe this channel */
    public function unsubscribe (User $user, Channel $channel) {
        $isUserTheOwner = $user->id === $channel->user_id;

        $isUserASubsciber = $channel->subscribers()->where('user_id', $user->id)->exists();

        if($isUserTheOwner){
            throw new UnAuthorizedToMakeActionMyException('You are the owner of this channel.');
        }

        if(!$isUserASubsciber){
            throw new UnAuthorizedToMakeActionMyException('You are not a subscriber in this channel.');
        }

        return true;
    }
}
