<?php

use Illuminate\Database\Seeder;
use App\Menu;

class MenuTableSeeder extends Seeder
{
    
    public function run()
    {
        $menu = new Menu();
        $menu->name = 'Lunes 6';
        $menu->is_forever = false;
        $menu->date = '2020-01-06';
        $menu->save();

        $menu = new Menu();
        $menu->name = 'Martes 7';
        $menu->is_forever = false;
        $menu->date = '2020-01-07';
        $menu->save();

        $menu = new Menu();
        $menu->name = 'Martes 8';
        $menu->is_forever = false;
        $menu->date = '2020-01-08';
        $menu->save();

        $menu = new Menu();
        $menu->name = 'Miercoles 9';
        $menu->is_forever = false;
        $menu->date = '2020-01-09';
        $menu->save();

        $menu = new Menu();
        $menu->name = 'Fijo 1';
        $menu->is_forever = true;
        $menu->date = '2020-01-01';
        $menu->save();

        $menu = new Menu();
        $menu->name = 'Fijo 2';
        $menu->is_forever = true;
        $menu->date = '2020-01-01';
        $menu->save();

        $menu = new Menu();
        $menu->name = 'Fijo 3';
        $menu->is_forever = true;
        $menu->date = '2020-01-01';
        $menu->save();

    }
}
