<?php

use Illuminate\Database\Seeder;
use App\Zone;

class ZoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zone = new Zone();
        $zone->name = 'Zona Centro (A)';
        $zone->save();

        $zone = new Zone();
        $zone->name = 'Zona Sur (B)';
        $zone->save();

        $zone = new Zone();
        $zone->name = 'Zona Suroeste (C)';
        $zone->save();

        $zone = new Zone();
        $zone->name = 'Zona Oeste (D)';
        $zone->save();

        $zone = new Zone();
        $zone->name = 'Zona Norte (E)';
        $zone->save();
    }
}
