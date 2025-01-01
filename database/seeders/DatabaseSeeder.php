<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // UserSeeder::class,
            // AdminSeeder::class,
            // CatagorySeeder::class,
            // ChannelSeeder::class,
            // BlogSeeder::class,
            //LikeSeeder::class,
            CommentSeeder::class
        ]);
    }
}
