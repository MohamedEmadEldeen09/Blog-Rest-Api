<?php

namespace Database\Seeders;

use App\Enums\AppConstantsEnum;
use App\Models\Blog;
use App\Models\Channel;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        /* Seed demo likes. */
        $this->seedDemoLikes();

        /* make a blog as trending. */
        $this->seedMulitipleLikesOnABlog();
    }

    public function seedDemoLikes(): void
    {
        $channels = Channel::all('id');

        foreach ($channels as $channel) {
            foreach ($channel->blogs as $blog) {
                // just make one subscriber like the blog
                $subscriber = $channel->subscribers()->first();

                if($subscriber){
                    Like::factory(1)->create([
                        "user_id" => $subscriber->id,
                        "blog_id" => $blog->id
                    ]);
                }
            }
        }
    }

    /* seed mulitple like in a single blog to test the trending functionality */
    public function seedMulitipleLikesOnABlog () {
        $mainChannel = Channel::where('name', AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value)->first();

        /* random blog on that channel */ 
        $blog = $mainChannel->blogs()->first();

        /* random users */
        $users = $mainChannel->subscribers()->limit(8)->get();

        foreach ($users as $user) {
            /* to skip the users whose liked the blog */
            if($blog->likes()->where('user_id', $user->id)->exists()){
                continue;
            }

            Like::factory(1)->create([
                "user_id" => $user->id,
                "blog_id" => $blog->id
            ]);
        }
    }
}
