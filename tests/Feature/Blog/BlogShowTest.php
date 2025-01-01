<?php

/**
 * For Admin
 * - admin can see the channel blog even if he is not subscribed to this channel
 */

use App\Models\Admin;
use App\Models\Blog;
use App\Models\Catagory;
use App\Models\Channel;
use App\Models\User;

/**
 * Show method Test Cases
 * - if the channel is the main app channel so there is no need to auth
 * - but if not make sure that this user is subscribed to that channel to see this blog 
 * - response json -->> body - status code
 */

it('admin can see the blog even he is not subscribed to this channel', function () {
    $user = User::factory()->create();

    $admin = Admin::factory()->create();

    $catagory = Catagory::factory()->create();

    $channel = Channel::factory()->create([
        'user_id' => $user->id
    ]);

    Blog::factory()->count(1)->create([
        'user_id' => $user->id,
        'channel_id' => $channel->id,
        'catagory_id' => $catagory->id
    ]);

    $response = $this->actingAs($admin)->getJson(route('channel.blog.show', [
        'channel' => $channel->id,
        'blog' => $channel->blogs()->first()->id
    ]));

    $response->assertOk(200);
});

it('can see specific response body' , function () {
    $user = User::factory()->create();

    $catagory = Catagory::factory()->create();

    $channel = Channel::factory()->create([
        'user_id' => $user->id
    ]);

    Blog::factory()->count(1)->create([
        'user_id' => $user->id,
        'channel_id' => $channel->id,
        'catagory_id' => $catagory->id
    ]);

    $channel->subscribers()->sync($user->id); 

    $response = $this->actingAs($user)->getJson(route('channel.blog.show',[
        'channel' => $channel->id,
        'blog' => $channel->blogs()->first()->id
    ]));

    $response->assertJsonStructure([
        'blog' => [
            'id',

            'title',

            'content',

            'likes' => [
                '*' => [
                    'id',
                    'type',
                    'created_at',
                    'user'
                ]
            ],

            'likes_count',

            'comments' => [
                '*' => [
                    'id',
                    'content',
                    'created_at',
                    'user'
                ]
            ],

            'comments_count',

            'images' => [
                '*' => [
                    'id',
                    'url',
                    'created_at'
                ]
            ],
            
            'catagory' => [
                'id',
                'name',
            ],

            'channel' => [
                'id',
                'name',
                'created_at'
            ],

            'author' => [
                'id',
                'name',
                'email',
                'email_verified_at',
            ]
        ]
    ])->assertOk();
});

it('user can not see the blog if he is not subscribed to the channel', function () {
    $user = User::factory()->create();

    $catagory = Catagory::factory()->create();

    $channel = Channel::factory()->create([
        'user_id' => $user->id
    ]);

    Blog::factory()->count(1)->create([
        'user_id' => $user->id,
        'channel_id' => $channel->id,
        'catagory_id' => $catagory->id
    ]);

    $response = $this->actingAs($user)->getJson(route('channel.blog.show', [
        'channel' => $channel->id,
        'blog' => $channel->blogs()->first()->id
    ]));

    /* can not see because he is not subscribed to this channel */
    $response->assertForbidden(); 

    /* now he subscribed to this channel */
    $channel->subscribers()->sync($user->id); 

    $response = $this->actingAs($user)->getJson(route('channel.blog.index', [
        'channel' => $channel->id,
    ]));

    /* now he can see the blog */
    $response->assertOk(); 
});
