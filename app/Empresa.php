<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $name = 'nombre';
    protected $address = 'direccion';
    protected $phone = 'telefono';
}
