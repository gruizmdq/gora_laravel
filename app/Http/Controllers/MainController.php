<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class MainController extends Controller
{
    public function get_empleados($id)
    {   
        $users = User::where('id_empresa', $id)
                        ->get();
        return response()->json(array('users'=> $users), 200);
    }
}
