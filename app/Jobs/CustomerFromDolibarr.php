<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Dolibarr\Societe;
use App\Models\Dolibarr\SocieteExtrafields;

class CustomerFromDolibarr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $fecha_importacion_clientes = now()->format('Y-m-d H:i:s');

        Log::channel('importacion_clientes')
            ->info('------------------------------ ' . $fecha_importacion_clientes . ' ------------------------------');
        Log::channel('importacion_clientes')->info('Inicio proceso de importación de clientes.');

        $customersIDs = SocieteExtrafields::where('tickets', 1)->get();
        foreach ($customersIDs as $id){
            $societe = Societe::where('rowid', $id->fk_object)
                                ->where('client', '!=', 0)
                                ->first();
                               
            if($societe!=null){
                $customerExists = Customer::where('cif', 'LIKE', '%' . $societe->siren . '%')
                                            ->orWhere('cif', 'LIKE', '%' . $societe->tva_intra . '%')
                                            ->exists();
    
                if(!$customerExists){      // (NO EXISTE EN CNT_TICKETS)
                    $email = $societe->email;
                    $cif = !$societe->siren ? $societe->siren : $societe->tva_intra;
    
                    DB::beginTransaction();
                    try{
                        $customerCreated = Customer::create([
                            'cif' => $cif,
                            'name' => $societe->nom,
                            'alias' => $societe->name_alias,
                            'email' => $email ?? null,
                            'address' => $societe->address ?? null,
                            'town' => $societe->town ?? null,
                            'postcode' => $societe->zip ?? null,
                            'phone' => $societe->phone ?? null,
                            'is_active' => 1,
                            'created_at' => now()->format('Y-m-d H:m:s'),
                            'updated_at' => now()->format('Y-m-d H:m:s')
                        ]);
    
                        // Propietario con varios clientes con mismo email (MrNoodless)
                        // El email es único por lo que si ya existe, generamos uno aleatorio.
                        $userNameExists = User::where('username' , 'LIKE' , $email)->exists();
                        if($userNameExists){
                            $aux_email = $email;
                            $email_arr = explode('@', $aux_email);
                            $numeros = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                            $letras = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
                            $random = str_replace($numeros, $letras, Str::random(8));
                            $random_lower = Str::lower($random);
                            $email_2 = $email_arr[0] . '@' . $random_lower . '.com';
                        }
                        $userCreated = User::create([
                            'customer_id' => $customerCreated->id,
                            'name' => $customerCreated->name,
                            'username' => ($userNameExists == true) ? $email_2 : $email,
                            'password' => bcrypt($cif),
                            'email' => $email,
                            'is_active' => 1,
                            'created_at' => now()->format('Y-m-d H:m:s'),
                            'updated_at' => now()->format('Y-m-d H:m:s')
                        ]);
                        $userCreated->syncRoles(Role::find(3));
                        $userCreated->save();
    
                        DB::commit();
                        Log::channel('importacion_clientes')->info('  Cliente ' . $societe->nom . ' con cif ' . $cif . ' creado correctamente.');
                    }
                    catch(\Exception $e){
                        Log::channel('importacion_clientes')->info('  Fallo en la importación (Cliente CNT_TICKET NO existente). Se hace ROLLBACK.');
                        Log::channel('importacion_clientes')->info($e); 
                        DB::rollback();
                    }
                }
                // (EXISTE EN CNT_TICKETS)
                else{       
                    Log::channel('importacion_clientes')->info('  El cliente ' . $societe->nom . ' ya existe en BD');      
                }
            }
        }

        Log::channel('importacion_clientes')->info('Proceso de importación de clientes finalizado.');
        Log::channel('importacion_clientes')->info('  ');
        Log::channel('importacion_clientes')->info('  ');
    }
}
