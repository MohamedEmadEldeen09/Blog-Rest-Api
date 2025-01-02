<?php

use App\Models\Catagory;
use App\Models\Channel;
use App\Models\User;

/**
 * ----------------- Test Cases -----------------
 * 1- user can not store a blog if he not authenticated or not subscribed to this channel
 * 2- user can store a blog if he authenticated and subscribed to this channel
 * 3- user can store the blog normally without subscribtion if he is the owner of this channel
 * 4- user can not store a blog if the request body is not valid
 * ---------------------------------------------
 */

describe('Test the blog storing process', function () {
    it('user can not store a blog if he not authenticated or not subscribed to this channel', function () {
        $channelOwner = User::factory()->create();
    
        $user = User::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $channelOwner->id
        ]);
    
        $response = $this->postJson(route('channel.blog.store', [
            'channel' => $channel->id,       
        ]),[
            'title' => 'Test Title',
            'content' => 'Test Content',
            'catagory_id' => $catagory->id,
        ]);
    
        $response->assertUnauthorized();
    
        $response = $this->actingAs($user)->postJson(route('channel.blog.store', [
            'channel' => $channel->id,       
        ]), [
            'title' => 'Test Title',
            'content' => 'Test Content',
            'catagory_id' => $catagory->id,
        ]);
    
        $response->assertForbidden();
    });
    
    it('user can store a blog if he authenticated and subscribed to this channel', function () {
        $channelOwner = User::factory()->create();
    
        $user = User::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $channelOwner->id
        ]);
    
        $channel->subscribers()->sync($user->id);
    
        $response = $this->actingAs($user)->postJson(route('channel.blog.store', [
            'channel' => $channel->id,       
        ]), [
            'title' => 'Test Title',
            'content' => 'Test Content',
            'catagory_id' => $catagory->id,
        ], [
            'Accept' => 'application/json',
        ]);
    
        $response->assertCreated();
    
        $response->assertJsonFragment([
            'message' => 'Blog created successfully.',
        ]);
    
        $response->assertJsonStructure([
            'message',
            'blog',
        ]);
    });
    
    it('user can store the blog normally without subscribtion if he is the owner of this channel', function () {
        $channelOwner = User::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $channelOwner->id
        ]);
    
        $response = $this->actingAs($channelOwner)->postJson(route('channel.blog.store', [
            'channel' => $channel->id,       
        ]), [
            'title' => 'Test Title',
            'content' => 'Test Content',
            'catagory_id' => $catagory->id,
        ]);
    
        $response->assertCreated();
    
        $this->assertDatabaseHas('blogs', [
            'title' => 'Test Title',
            'content' => 'Test Content',
        ]);

        $this->assertDatabaseCount('blogs', 1);
    });
    
    it('user can not store a blog if the request body is not valid', function () {
        $channelOwner = User::factory()->create();
    
        $user = User::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $channelOwner->id
        ]);
    
        $channel->subscribers()->sync($user->id);
        
        $response = $this->actingAs($user)->postJson(route('channel.blog.store', [
            'channel' => $channel->id,       
        ]), [
            'title' => 'Test Title',
            'catagory_id' => $catagory->id,
        ]);
    
        $response->assertJsonValidationErrors(['content']);
    
        $response->assertUnprocessable();
    });    
});

