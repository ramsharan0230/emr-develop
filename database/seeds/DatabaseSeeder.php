<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
//        $script = getcwd().'/database/seeds/db-files/email_templates.sql';
//        DB::unprepared(file_get_contents($script));
//        $this->command->info('Cogent DB Seeder');


        $this->call(PermissionPackageTableSeeder::class);
//        $this->call(UserSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(OptionSeeder::class);
//        $this->call(RequestMacAccessSeeder::class);
//        $this->call(MachineMappingSeeder::class);
        $this->call(HospitalAdmin::class);
        $this->call(NewUserSeeder::class);
        $this->call(ModulesSeeder::class);
//        $script = getcwd().'/database/seeds/db-files/email_templates.sql';
//        DB::unprepared(file_get_contents($script));
//        $this->command->info('Cogent DB Seeder');
    }
}
