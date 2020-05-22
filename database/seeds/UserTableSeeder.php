<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserTableSeeder extends Seeder
{
    
    public function run()
    {
        $user = new User();
        $user->id_empresa = 1;
        $user->name = 'Gora Kitchen';
        $user->email = 'root@root.com';
        $user->password = Hash::make('rootroot');
        $user->is_admin = true;
        $user->save();

        $user = new User();
        $user->id_empresa = 2;
        $user->name = 'Empleado 1';
        $user->email = 'empleado@empleado.com';
        $user->password = Hash::make('rootroot');
        $user->is_admin = false;
        $user->save();

        $user = new User();
        $user->id_empresa = 2;
        $user->name = 'Admin empresa';
        $user->email = 'empresa@empresa.com';
        $user->password = Hash::make('rootroot');
        $user->is_admin = false;
        $user->save();
    }
}