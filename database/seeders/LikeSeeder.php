<?php

namespace Database\Seeders;

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
    }

    public function seedDemoLikes(): void
    {
        $channels = Channel::all('id');

        foreach ($channels as $channel) {
            foreach ($channel->blogs() as $blog) {
                foreach ($channel->subscribers() as $subscriber) {
                    Like::factory(1)->create([
                        "user_id" => $subscriber->id,
                        "blog_id" => $blog->id
                    ]);
                }
            }
        }
    }
}
