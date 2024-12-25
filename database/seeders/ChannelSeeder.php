<?php

namespace Database\Seeders;

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
        $mainuser = User::where('email', 'channel@example.com')->first();
        Channel::create([
            "name" => $mainuser->name,
            "user_id" => $mainuser->id
        ]);
        

        /* demo channels for testing the functionality */
        $userIds = User::pluck("id");
        $theMainAppChannel = Channel::where('name', 'Main App Channel')->first();
        foreach ($userIds as $userId) {
            /* to escape the main app channel */
            if($userId !== $theMainAppChannel->user_id){
                Channel::factory(3)->create([
                    "user_id" => $userId
                ]);
            }
        }
        

        /* 
        | to join all the users to the main app channel except of cource 
        | the author user_id who created the channel which is in this 
        | case the main app user 
        */
        $theMainAppChannel = Channel::where('name', 'Main App Channel')->first();
        $usersIds = User::where('id', '!=', $theMainAppChannel->user_id)->pluck('id');
        $theMainAppChannel->subscribers()->sync($usersIds);


        /* to join subscribers to a channel */
        $faker = Faker::Create();
        $theMainAppChannel = Channel::where('name', 'Main App Channel')->first();
        $channels = Channel::where('name', '!=', $theMainAppChannel->name)->get();
        $usersIds = User::where('id', '!=', $theMainAppChannel->user_id)->pluck('id');
        foreach($channels as $channel){
            $randomIds = $faker->randomElements($usersIds, 3);

            /* to escape the author id of the channel */
            $subscribers = array();
            foreach ($randomIds as $randomId) {
                if($randomId !== $channel->user_id){
                    array_push($subscribers, $randomId);
                }
            }

            $channel->subscribers()->sync($subscribers);
        }   


        /* to rest or remove all the records for testing purposes */
        //$this->removeAllTheRecords();
    }

    public function removeAllTheRecords () {
        $channelsIds = Channel::pluck('id');
        Channel::destroy($channelsIds);
    }
}
