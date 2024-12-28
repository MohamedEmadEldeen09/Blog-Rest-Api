<?php

namespace App\Policies;

use App\Enums\AppConstantsEnum;
use App\Exceptions\UnAuthorizedToMakeActionMyException;
use App\Models\Blog;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{
    /**
     * Admin Layer
     * if the user is the main app channel ( admin )
     **/
    public function before(User $user, $ability)
    {
        $user = request()->user('sanctum');

        if($user->email === AppConstantsEnum::MAIN_APP_CHANNEL_EMAIL->value){
            return true;
        }
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

        $user = request()->user('sanctum');

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

        $user = request()->user('sanctum');

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
        $user = request()->user('sanctum');

        return $blog->user_id === $user->id
            ? true
            : throw new UnAuthorizedToMakeActionMyException('You must be the author of the blog to delete it.');
    }
}
