<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PriorityController extends Controller
{
    public function get_all_priorities(): JsonResponse
    {
        return response()->json([ 'priorities' => Priority::get()->toArray() ]);
    }
}
