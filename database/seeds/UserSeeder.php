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
        $user->name = 'Emilio Ochoa';
        $user->email = 'emilioaor@gmail.com';
        $user->password = bcrypt('123456');
        $user->role = \App\User::ROLE_ADMIN;
        $user->save();

        $user = new \App\User();
        $user->name = 'Jose Luis Zreik';
        $user->email = 'jlzreik@yezzcorp.com';
        $user->password = bcrypt('123456');
        $user->role = \App\User::ROLE_ADMIN;
        $user->save();

        if (config('app.env') === 'local') {
            $user = new \App\User();
            $user->name = 'Test Finances';
            $user->email = 'finances@gmail.com';
            $user->password = bcrypt('123456');
            $user->role = \App\User::ROLE_FINANCES;
            $user->save();
        }
    }
}
