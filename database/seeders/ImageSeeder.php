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
        /* insert random profile image for each user */
        //$this->seedUsersProfileImage();

        /* insert random blog images for each blog */
        //$this->seedBlogsImages();

        /* to rest or remove all the records for testing purposes */
        $this->removeAllTheRecords();
    }

    /* delete all the records */
    public function removeAllTheRecords () {
        $imagesIds = Image::pluck('id');
        Image::destroy($imagesIds);
    }

    /* insert random profile image for each user */
    public function seedUsersProfileImage () {
        $faker = Faker::Create();
        
        $users = User::all('id');
        foreach ($users as $user) {
            $user->image()->create([
                "url" => $faker->imageUrl(100, 100, $user->name, true)
            ]);
        }
    }

    /* insert random blog images for each blog */
    public function seedBlogsImages () {
        $faker = Faker::Create();
        
        $blogs = Blog::all('id');
        foreach ($blogs as $blog) {
            /* insert 2 random images for each blog */
            for ($i = 0; $i < 2; $i++) {
                $blog->images()->create([
                    "url" => $faker->imageUrl(640, 480, $blog->title, true)
                ]);
            }
        }
    }
}
