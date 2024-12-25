<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* main user for the golbal channel */
        User::factory()->create([
            'name' => 'Main App Channel',
            'email' => 'channel@example.com',
        ]);

        /* demo user for testing the api in production */
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        /* demo users for testing the fuctionality of the app */
        User::factory(20)->create();

        /* to rest or remove all the records for testing purposes */
        //$this->removeAllTheRecords();
    }

    public function removeAllTheRecords () {
        $channelsIds = User::pluck('id');
        User::destroy($channelsIds);
    }
}
