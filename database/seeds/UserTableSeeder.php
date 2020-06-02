<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserTableSeeder extends Seeder
{
    
    public function run()
    {
        $user = new User();
        $user->name = 'Start Calzados';
        $user->email = 'root@root.com';
        $user->password = Hash::make('rootroot');
        $user->is_admin = true;
        $user->save();

        $user = new User();
        $user->name = 'Moli Moli';
        $user->email = 'molimoli@molimoli.com';
        $user->password = Hash::make('rootroot');
        $user->is_admin = false;
        $user->save();

        $user = new User();
        $user->name = 'GOYO GOYO';
        $user->email = 'goyo@goyo.com';
        $user->password = Hash::make('rootroot');
        $user->is_admin = false;
        $user->save();
    }
}