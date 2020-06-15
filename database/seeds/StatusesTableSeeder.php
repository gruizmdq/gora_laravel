<?php

use Illuminate\Database\Seeder;
use App\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = new Status();
        $status->name = 'Nuevo';
        $status->color = 'white';
        $status->save();

        $status = new Status();
        $status->name = 'Incomunicado';
        $status->color = 'red';
        $status->save();

        $status = new Status();
        $status->name = 'Por coordinar';
        $status->color = 'yellow';
        $status->save();

        $status = new Status();
        $status->name = 'Entregado';
        $status->color = 'green';
        $status->save();
    }
}
