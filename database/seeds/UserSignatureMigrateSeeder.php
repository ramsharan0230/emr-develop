<?php

use Illuminate\Database\Seeder;

class UserSignatureMigrateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersData = \App\CogentUsers::select('username')->get();
        foreach ($usersData as $userDaton) {
            $userSignature = \App\ReportUser::select('fldsigimage', 'flduserid')->where('flduserid', $userDaton->username)->first();
            if ($userSignature && $userSignature->flduserid)
                \App\CogentUsers::select('username')->where('username', $userSignature->flduserid)->update(['signature_image' => $userSignature->fldsigimage]);
        }
    }
}
