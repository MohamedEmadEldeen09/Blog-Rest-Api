<?php

use App\Models\Admin;
use App\Models\Blog;
use App\Models\Catagory;
use App\Models\Channel;
use App\Models\User;

/**
 * ----------------- Test Cases -----------------
 * 1. ensure that only the owner of the blog can delete his blog
 * 2. ensure that the blog is deleted from the database
 * 3. admin can delete the blog even being not the owner of the blog
 * ---------------------------------------------
 */

describe('Test the blog destroying process', function () {
    it('ensure that only the owner of the blog can delete his blog', function () {
        $channelOwner = User::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $channelOwner->id
        ]);
    
        $blogOwner = User::factory()->create();
    
        $channel->subscribers()->sync($blogOwner->id);
        
        $blog = Blog::factory()->create([
            'channel_id' => $channel->id,
            'catagory_id' => $catagory->id,
            'user_id' => $blogOwner->id
        ]);

        /* let us create another user where he does not own this blog*/
        $strangeUser = User::factory()->create();
    
        $channel->subscribers()->sync($strangeUser->id);
        
        $response = $this->actingAs($strangeUser)
            ->deleteJson(route('channel.blog.destroy', [
                'channel' => $channel->id,
                'blog' => $blog->id,
            ]
        ));
    
        $response->assertForbidden(); // because he is not the owner of the blog
    
        /* the owner of the blog */
        $response = $this->actingAs($blogOwner)
            ->deleteJson(route('channel.blog.destroy', [
                'channel' => $channel->id,
                'blog' => $blog->id,
            ]
        ));
    
        $response->assertNoContent(); // because he is the owner of the blog
    });

    it('ensure that the blog is deleted from the database', function () {
        $channelOwner = User::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $channelOwner->id
        ]);
    
        $blogOwner = User::factory()->create();
    
        $channel->subscribers()->sync($blogOwner->id);
        
        $blog = Blog::factory()->create([
            'channel_id' => $channel->id,
            'catagory_id' => $catagory->id,
            'user_id' => $blogOwner->id
        ]);

        $response = $this->actingAs($blogOwner)
            ->deleteJson(route('channel.blog.destroy', [
                'channel' => $channel->id,
                'blog' => $blog->id,
            ]
        ));
    
        $response->assertNoContent(); 

        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id,
        ]);
        
        //$this->assertModelMissing($blog);

        //$this->assertDatabaseEmpty('blogs');
    });

    it('admin can delete the blog even being not the owner of the blog', function () {
        $channelOwner = User::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $channelOwner->id
        ]);
    
        $blogOwner = User::factory()->create();
    
        $channel->subscribers()->sync($blogOwner->id);
        
        $blog = Blog::factory()->create([
            'channel_id' => $channel->id,
            'catagory_id' => $catagory->id,
            'user_id' => $blogOwner->id
        ]);

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin)
            ->deleteJson(route('channel.blog.destroy', [
                'channel' => $channel->id,
                'blog' => $blog->id,
            ]
        ));
    
        $response->assertNoContent(); 

        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id,
        ]);
    });
});
