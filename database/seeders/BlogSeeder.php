<?php

namespace Database\Seeders;

use App\Enums\AppConstantsEnum;
use App\Models\Blog;
use App\Models\Catagory;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Seed demo blogs. */
        $this->seedDemoBlogs();
    }

    public function seedDemoBlogs(): void
    {
        $catagoriesIds = Catagory::pluck('id');

        $channelsIds = Channel::pluck('id');

        $theMainAppChannel = Channel::where('name', 
            AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value)->first();

        /* Get all users except the main app user. */
        $usersIds = User::where('id', '!=', $theMainAppChannel->user_id)->pluck('id');
        
        foreach ($catagoriesIds as $catagoryId) {
            foreach ($channelsIds as $channelId) {
                foreach ($usersIds as $userId) { 
                    Blog::factory(4)->create([
                        'user_id' => $userId,
                        'catagory_id' => $catagoryId,
                        'channel_id' => $channelId,
                    ]);
                }
            }
        }
    }
}
