<?php

namespace Database\Seeders;

use App\Enums\AppConstantsEnum;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        /* global channel */
        $this->seedTheMainAppChannel();

        /* demo channels for testing the functionality */
        $this->seedDemoChannels();
        
        /* 
        | to join all the users to the main app channel except of cource 
        | the author user_id who created the channel which is in this 
        | case the main app user 
        */
        $this->joinAllUsersToTheMainAppChannel();

        /* to join subscribers to a channel */
        $this->joinSubscribersToChannel();

        /* to rest or remove all the records for testing purposes */
        //$this->removeAllTheRecords();
    }

    public function seedTheMainAppChannel () {
        $mainuser = User::where('email', 
            AppConstantsEnum::MAIN_APP_CHANNEL_EMAIL->value)->first();

        Channel::create([
            "name" => $mainuser->name,
            "user_id" => $mainuser->id
        ]);
    }

    public function seedDemoChannels () {
        $userIds = User::pluck("id");

        $theMainAppChannel = Channel::where('name'
            , AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value)->first();

        foreach ($userIds as $userId) {
            /* to escape the main app channel */
            if($userId !== $theMainAppChannel->user_id){
                Channel::factory(1)->create([
                    "user_id" => $userId
                ]);
            }
        }
    }

    public function joinAllUsersToTheMainAppChannel () {
        $theMainAppChannel = Channel::where('name', 
            AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value)->first();

        $usersIds = User::pluck('id');

        $theMainAppChannel->subscribers()->sync($usersIds);
    }

    public function joinSubscribersToChannel () {
        $faker = Faker::Create();

        $theMainAppChannel = Channel::where('name', 
            AppConstantsEnum::MAIN_APP_CHANNEL_NAME->value)->first();

        $channels = Channel::where('name', '!=', $theMainAppChannel->name)->get();

        $usersIds = User::where('id', '!=', $theMainAppChannel->user_id)->pluck('id');

        foreach($channels as $channel){
            $randomIds = $faker->randomElements($usersIds, 3);

            /* to escape the author id of the channel */
            $subscribersIds = array();
            foreach ($randomIds as $randomId) {
                if($randomId !== $channel->user_id){
                    array_push($subscribersIds, $randomId);
                }
            }

            $channel->subscribers()->sync($subscribersIds);
        }   
    }

    public function removeAllTheRecords () {
        $channelsIds = Channel::pluck('id');
        Channel::destroy($channelsIds);
    }
}
