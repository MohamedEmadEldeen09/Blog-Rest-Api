<?php

namespace App\Policies;

use App\Enums\AppConstantsEnum;
use App\Exceptions\UnAuthorizedToMakeActionMyException;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class BlogPolicy
{
    /* check if the user type is admin. */
    public function before(?Model $model, $ability)
    {   
        /**
         * if the user is admin he can do anything except 
         * the ability of updating a blog
         */
        if($model && $model->isAdmin()){
            if($ability == 'update'){
                throw throw new UnAuthorizedToMakeActionMyException('Even if you are an admin you can not update the user blog.');
            }

            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user, Channel $channel): bool
    {
        /* 
        | first case if the channel is the main app channel
        | the user can see it weither he logged in or not
        */
        if($channel->name === AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value){    
            return true;
        }

        /* 
        | second case if the user logged in so he can see only the blogs that is 
        | in the main app channel and the channel that he subscribed to
        */
        return $channel->subscribers()->where('user_id', $user->id)->exists() 
            ? true
            : throw new UnAuthorizedToMakeActionMyException('You must be subscribed to the channel to view its blogs.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Blog $blog, Channel $channel): bool
    {  
        if($channel->name === AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value){    
            return true;
        }

        if(! $user){
            throw new UnAuthorizedToMakeActionMyException('Sign in to see the blog if you have access to it.');
        }

        return $channel->subscribers()->where('user_id', $user->id)->exists()
            ? true
            : throw new UnAuthorizedToMakeActionMyException('You must be subscribed to the channel to view the blog.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Channel $channel): bool
    {
        if($channel->name === AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value){    
            return true;
        }
        
        return $channel->subscribers()->where('user_id', $user->id)->exists()
            ? true
            : throw new UnAuthorizedToMakeActionMyException('You must be subscribed to the channel to create a blog.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Blog $blog): bool
    {
        return $blog->user_id === $user->id
            ? true
            : throw new UnAuthorizedToMakeActionMyException('You must be the author of the blog to update it.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Blog $blog): bool
    {
        return $blog->user_id === $user->id
            ? true
            : throw new UnAuthorizedToMakeActionMyException('You must be the author of the blog to delete it.');
    }
}
