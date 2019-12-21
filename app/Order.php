<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $id_empleado = 'empleado';
    protected $id_menu = 'menu';
    protected $status = 'estado';
    protected $comments = 'comentarios';
    protected $date = 'fecha';

    protected $casts = [
        'fecha' => 'datetime:d/m/Y',
    ];

    public function today_orders($query){
        return $query->where('fecha', '=', (new DateTime('NOW'))->format('d/m/Y'));
    }
}
