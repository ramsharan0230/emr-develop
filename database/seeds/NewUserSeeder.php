<?php

use Illuminate\Database\Seeder;

class NewUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCreateData['firstname'] = 'Cogent';
        $userCreateData['username'] = "cogent";
        $userCreateData['flduserid'] = "cogent";
        $userCreateData['password'] = "099111103101110116095097100109105110";
        $userCreateData['status'] = "active";
        $userCreateData['xyz'] = 0;

        $user = \App\CogentUsers::create($userCreateData);
        \App\UserGroup::create(['user_id' => $user->id, 'group_id' => 1]);
    }
}
