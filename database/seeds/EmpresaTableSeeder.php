<?php

use Illuminate\Database\Seeder;
use App\Empresa;

class EmpresaTableSeeder extends Seeder
{
    public function run()
    {
        $empresa = new Empresa();
        $empresa->name = 'Gora Kitchen';
        $empresa->address = 'Colón 1234';
        $empresa->phone = '123456789';
        $empresa->save();

        $empresa = new Empresa();
        $empresa->name = 'Making Sense';
        $empresa->address = 'Colón 1234';
        $empresa->phone = '123456789';
        $empresa->save();

        $empresa = new Empresa();
        $empresa->name = 'Globant';
        $empresa->address = 'Colón 1234';
        $empresa->phone = '123456789';
        $empresa->save();
    }
}