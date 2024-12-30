<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* seed the main blog app admin */
        $this->seedMainAdmin();
    }

    /* admin */
    public function seedMainAdmin () : void {
        Admin::create([
            "name" => "admin",
            "email" => "admin@gmail.com",
            "password" => Hash::make('admin_2025'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
