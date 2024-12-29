<?php

namespace Database\Seeders;

use App\Models\Catagory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatagorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                "name" => "history"
            ],
            [
                "name" => "science"
            ],
            [
                "name" => "eduacatoin"
            ],
            [
                "name" => "sport"
            ],
            [
                "name" => "computer Science"
            ],
            [
                "name" => "romance"
            ],
            [
                "name" => "news"
            ],
            [
                "name" => "economy"
            ],
            [
                "name" => "fasoin"
            ],
            [
                "name" => "travel"
            ],
        ];

        collect($categories)->each(function ($category) { 
            Catagory::create($category); 
        });
    }
}
