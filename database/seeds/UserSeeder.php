<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->email = 'emilioaor@gmail.com';
        $user->password = bcrypt('123456');
        $user->role = \App\User::ROLE_ADMIN;
        $user->save();
    }
}
