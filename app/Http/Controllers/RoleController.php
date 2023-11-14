<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function get_all_roles(): JsonResponse
    {
        return response()->json([ 'roles' => Role::all() ], 200);
    }
}
