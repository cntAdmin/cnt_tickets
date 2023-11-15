<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Dolibarr\Societe;
use App\Models\Dolibarr\SocieteExtrafields;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ModelHasRoles;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function my_custom_tests()
    {
        // dd('hola cotilla');
        // $customersIDs = SocieteExtrafields::where('tickets', 1)->get();
        // $test = SocieteExtrafields::where('tickets', 1)->get();
        $societe = Societe::where('rowid', 1437)
                                ->where('client', '!=', 0)
                                ->first();
                                if($societe!=null) dd('no hay cliente');
        dd($societe);

        foreach ($test as $id){
            $societe = Societe::where('rowid', $id->fk_object)->first();
            dd($societe);

            $customerExists = Customer::where('cif', 'LIKE', '%' . $societe->siren . '%')
                                        ->orWhere('cif', 'LIKE', '%' . $societe->tva_intra . '%')
                                        ->exists();

            if(!$customerExists){ 
                dd($societe);
            }
        }
            
    }
}