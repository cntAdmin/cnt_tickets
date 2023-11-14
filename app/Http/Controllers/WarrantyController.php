<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WarrantyController extends Controller
{
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('waranties.index')->with([ 'waranties_count' => Warranty::count() ]);
        }
    }

    public function get_all_warranties(): JsonResponse
    {
        return response()->json([ 'warranties' => Warranty::all() ]);
    }
}
