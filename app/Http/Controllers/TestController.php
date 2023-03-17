<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ModelHasRoles;

class TestController extends Controller
{
    public function my_custom_tests()
    {
        // $users = User::all();
        // // dd($users);
        
        // foreach ($users as $user){
        //     // dd($user->id);

        //     ModelHasRoles::create([
        //         'role_id' => 3,
        //         'model_type' => 'App\Models\User',
        //         'model_id' => $user->id
        //     ]);
        // }
        dd('hola cotilla');
    }
}