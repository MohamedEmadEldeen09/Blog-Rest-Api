<?php

namespace Database\Seeders;

use App\Enums\AppConstantsEnum;
use App\Models\Image;
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
        /* seed fake users */
        $this->seedFakeUsers();

        /* to rest or remove all the records for testing purposes */
        //$this->removeAllRecords();
    }

    /* seed fake users */
    public function seedFakeUsers () {
        /* main user for the golbal channel */
        User::factory()->create([
            'name' => AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value,
            'email' => AppConstantsEnum::MAIN_APP_CHANNEL_EMAIL->value,
            'password' => AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value . '#2025',
        ]);

        // /* demo user for testing the api in production */
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        /* demo users for testing the fuctionality of the app */
        User::factory(15)->create();
    }

    /* delete all the users */
    public function removeAllRecords () {
        $usersIds = User::pluck('id');
        User::destroy($usersIds);
    }
}
