<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $name = 'nombre';
    protected $is_forever = 'es_fijo';
    protected $date = 'fecha';

    protected $attributes = [
        'is_forever' => false,
    ];
    
    protected $casts = [
        'date' => 'datetime:d/m/Y',
    ];
    
}
