<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(EmpresaTableSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(UserTableSeeder::class);
    }
}
