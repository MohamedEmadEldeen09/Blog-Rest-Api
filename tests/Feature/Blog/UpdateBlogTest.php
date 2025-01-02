<?php

use App\Models\Admin;
use App\Models\Blog;
use App\Models\Catagory;
use App\Models\Channel;
use App\Models\User;

/**
 * ----------------- Test Cases -----------------
 * 1- admin cannot access this route
 * 2- only user how own the blog can update it
 * 3- validate before update the blog
 * 4- check if the blog really updated in the database
 * ---------------------------------------------
 */

describe('Test the blog updating process', function () {
    it('admin cannot access this route', function () {
        $admin = Admin::factory()->create();
    
        $user = User::factory()->create();

        $catagory = Catagory::factory()->create();

        $channel = Channel::factory()->create([
            'user_id' => $user->id
        ]);
    
        $blog = Blog::factory()->create([
            'channel_id' => $channel->id,
            'catagory_id' => $catagory->id,
            'user_id' => $user->id
        ]);
    
        $newBlohUpdated = [
            'content' => 'new content',
        ];

        $response = $this->actingAs($admin)->patchJson(route('channel.blog.update', [
            'channel' => $channel->id,
            'blog' => $blog->id,
        ]), $newBlohUpdated);
    
        $response->assertForbidden();
    });

    it('only user how own the blog can update it', function () {
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

        $newBlogUpdated = [
            'content' => 'new content',
        ];
    
        /* let us create another user where he does not own this blog*/
        $strangeUser = User::factory()->create();
    
        $channel->subscribers()->sync($strangeUser->id);

        $response = $this->actingAs($strangeUser)->patchJson(route('channel.blog.update', [
            'channel' => $channel->id,
            'blog' => $blog->id,
        ]), $newBlogUpdated);
    
        $response->assertForbidden(); // because he is not the owner of the blog
    
        /* the owner of the blog */
        $response = $this->actingAs($blogOwner)->patchJson(route('channel.blog.update', [
            'channel' => $channel->id,
            'blog' => $blog->id,
        ]), $newBlogUpdated);
    
        $response->assertOk(); // because he is the owner of the blog
    });
    
    it('validate before update the blog', function () {
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

        $newBlogUpdated = [
            'content' => '',
        ];
    
        $response = $this->actingAs($blogOwner)->patchJson(route('channel.blog.update', [
            'channel' => $channel->id,
            'blog' => $blog->id,
        ]), $newBlogUpdated);
    
        $response->assertUnprocessable();
    
        $response->assertJsonValidationErrors(['content']);
    });

    it('check if the blog really updated in the database', function () {
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

        $newBlogUpdated = [
            'content' => 'new content',
        ];
    
        $response = $this->actingAs($blogOwner)->patchJson(route('channel.blog.update', [
            'channel' => $channel->id,
            'blog' => $blog->id,
        ]), $newBlogUpdated);
    
        $response->assertOk();
    
        $this->assertModelExists($blog);

        $this->assertDatabaseHas('blogs', [
            'content' => 'new content',
        ]);
    });
});

