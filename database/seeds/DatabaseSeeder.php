<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(StreetTableSeeder::class);
        $this->call(ZoneTableSeeder::class);
        $this->call(NeighborhoodsTableSeeder::class);
    }
}
