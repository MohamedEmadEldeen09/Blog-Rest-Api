<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::Create();

        /* insert random profile image for each user */
        // $users = User::all();
        // foreach ($users as $user) {
        //     $user->image()->create([
        //         "url" => $faker->imageUrl(100, 100, $user->name, true)
        //     ]);
        // }

        /* insert 2 random images for each blog */
        $blogs = Blog::all();
        foreach ($blogs as $blog) {
            for ($i=0; $i < 2; $i++) { 
                $blog->images()->create([
                    "url" => $faker->imageUrl(640, 480, "$blog->title"."$i", true)
                ]);
            }
        }

        /* to rest or remove all the records for testing purposes */
        //$this->removeAllTheRecords();
    }

    public function removeAllTheRecords () {
        $channelsIds = Image::pluck('id');
        Image::destroy($channelsIds);
    }
}
