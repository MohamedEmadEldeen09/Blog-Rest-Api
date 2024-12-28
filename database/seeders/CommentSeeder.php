<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* seed demo comments */
        $this->seedDemoComments();
    }

    public function seedDemoComments () : void
    {
        $channels = Channel::all();
        
        foreach ($channels as $channel) { 
            foreach ($channel->blogs() as $blog) {
                foreach ($channel->subscribers() as $subscriber) {
                    Comment::factory(1)->create([
                        "user_id" => $subscriber->id,
                        "blog_id" => $blog->id
                    ]);
                }
            }
        }
    }
}
