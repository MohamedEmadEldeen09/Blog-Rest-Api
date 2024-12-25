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
                "name" => "History"
            ],
            [
                "name" => "Science"
            ],
            [
                "name" => "Eduacatoin"
            ],
            [
                "name" => "Sport"
            ],
            [
                "name" => "Computer Science"
            ],
            [
                "name" => "Romance"
            ],
            [
                "name" => "News"
            ],
            [
                "name" => "Economy"
            ],
            [
                "name" => "Fasoin"
            ],
            [
                "name" => "Travel"
            ],
        ];

        collect($categories)->each(function ($category) { 
            Catagory::create($category); 
        });
    }
}
