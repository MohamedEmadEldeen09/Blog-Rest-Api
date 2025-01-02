<?php

use App\Enums\AppConstantsEnum;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\Catagory;
use App\Models\Channel;
use App\Models\User;

/**
 * ----------------- Test Cases -----------------
 * 1- admin can see all blogs inside any channel
 * 2- unauthenticated user can see the blogs of the main app channel
 * 3- can see specific response body if search parameters exist
 * 4- user can not see the blogs of the channel that he is not subscribed to
 * ---------------------------------------------
 */

describe('Test the blog index process', function () {
    it('admin can see all blogs inside any channel', function () {
        $user = User::factory()->create();
    
        $admin = Admin::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $user->id
        ]);
    
        Blog::factory()->count(3)->create([
            'user_id' => $user->id,
            'channel_id' => $channel->id,
            'catagory_id' => $catagory->id
        ]);
    
        $response = $this->actingAs($admin)->getJson(route('channel.blog.index', [
            'channel' => $channel->id,
        ]));
    
        $response->assertStatus(200)->assertJsonCount(3, 'blogs');
    });
    
    it('unauthenticated user can see the blogs of the main app channel', function () {
        /* main user for the main app channel */
        $user = User::factory()->create([
            'name' => AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value,
            'email' => AppConstantsEnum::MAIN_APP_CHANNEL_EMAIL->value,
            'password' => AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value . '#2025',
        ]);
    
        /* main app channel */
        $channel = Channel::create([
            "name" => $user->name,
            "user_id" => $user->id
        ]);
    
        $catagory = Catagory::factory()->create();
    
        Blog::factory()->count(3)->create([
            'user_id' => $user->id,
            'channel_id' => $channel->id,
            'catagory_id' => $catagory->id
        ]);
    
        $response = $this->getJson(route('channel.blog.index', [
            'channel' => $channel->id,
        ]));
    
        $response->assertOk()->assertJsonCount(3, 'blogs');
    
        $response->assertJsonStructure([
            'blogs' => [
                '*' => [
                    'id',
                    'title',
                    'catagory' => [
                        'id',
                        'name',
                    ],
                    'channel' => [
                        'id',
                        'name',
                        'created_at'
                    ],
                    'author_name',
                    'preview_image',
                    'likes_count',
                    'comments_count',
                    'created_at',
                ]
            ]
        ]);
    });
    
    it('can see specific response body if search parameters exist', function () {
        /* main user for the main app channel */
        $user = User::factory()->create([
            'name' => AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value,
            'email' => AppConstantsEnum::MAIN_APP_CHANNEL_EMAIL->value,
            'password' => AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value . '#2025',
        ]);
    
        /* main app channel */
        $channel = Channel::create([
            "name" => $user->name,
            "user_id" => $user->id
        ]);
    
        $catagory = Catagory::factory()->create();
    
        Blog::factory()->count(3)->create([
            'user_id' => $user->id,
            'channel_id' => $channel->id,
            'catagory_id' => $catagory->id
        ]);
    
        $response = $this->getJson(route('channel.blog.index', [
            'channel' => $channel->id,
            'catagory_name' => $catagory->name,
            'blog_search' => 'blog'
        ]));
    
        $response->assertJsonStructure([
            'blogs' => [
                '*' => [
                    'id',
                    'title',
                    'catagory_id',
                    'channel_id',
                    'user_id',
                    'created_at',
                ]
            ],
            'filters' => [
                'blog_search',
                'catagory_name'
            ]
        ])->assertOk();
    });
    
    it('user can not see the blogs of the channel that he is not subscribed to', function () {
        $user = User::factory()->create();
    
        $catagory = Catagory::factory()->create();
    
        $channel = Channel::factory()->create([
            'user_id' => $user->id
        ]);
    
        Blog::factory()->count(3)->create([
            'user_id' => $user->id,
            'channel_id' => $channel->id,
            'catagory_id' => $catagory->id
        ]);
    
        $response = $this->actingAs($user)->getJson(route('channel.blog.index', [
            'channel' => $channel->id,
        ]));
    
        $response->assertForbidden(); // because he is not subscribed to this channel
    
        $channel->subscribers()->sync($user->id); //now he subscribed to this channel
    
        $response = $this->actingAs($user)->getJson(route('channel.blog.index', [
            'channel' => $channel->id,
        ]));
    
        $response->assertOk(); //now he can see the blogs
    });
});

