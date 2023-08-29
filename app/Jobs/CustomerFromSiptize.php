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

use App\Traits\PeticionesSiptizeTrait;

class CustomerFromSiptize implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use PeticionesSiptizeTrait;

    public function handle()
    {
        $customers = $this->apiGetCustomers();
        $fecha_importacion_clientes = now()->format('Y-m-d H:i:s');

        Log::channel('importacion_clientes')
            ->info('------------------------------ ' . $fecha_importacion_clientes . ' ------------------------------');
        Log::channel('importacion_clientes')->info('Inicio proceso de importación de clientes.');

        // (ARRAY_CLIENTES)
        foreach ($customers['data'] as $item)
        {
            $id = $item['hutanId'];

            $apiCustomer = $this->apiGetCustomerById($id);   
            $customer_name = $apiCustomer["nombre"];
            $customer_alias = $apiCustomer['alias'];
            $cif = str_replace(' ', '', $apiCustomer['cif']);
            $customer_id = $apiCustomer['hutanId'];
            $is_active = ($apiCustomer['activo'] == true) ? 1 : 0;
            $email = !empty($apiCustomer['email']) ? $apiCustomer['email'] : $apiCustomer['emailDeNotification'];
            $email = str_replace(' ', '', $email);

            // Solicitamos a la API datos de dirección del cliente
            $apiCustomerDir = $this->apiGetDireccionById($apiCustomer['direccion']['hutanId']);

            Log::channel('importacion_clientes')->info('  Cliente => ' . $customer_name);

            // Fecha actualización en siptize.
            $cnt_tickets_customer = Customer::where('cif', $cif)->first();
            if(!$cnt_tickets_customer){      // (NO EXISTE EN CNT_TICKETS)
                
                DB::beginTransaction();
                try{
                    $customerCreated = Customer::create([
                        'cif' => $cif,
                        'name' => $customer_name,
                        'alias' => $customer_alias,
                        'email' => $email,
                        'address' => $apiCustomerDir['direccion'] ?? null,
                        'town' => $apiCustomerDir['ciudad'] ?? null,
                        'postcode' => $apiCustomerDir['codigoPostal'] ?? null,
                        'phone' => $apiCustomer['telefono'] ?? null,
                        'is_active' => $is_active,
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
                        'is_active' => $is_active,
                        'created_at' => now()->format('Y-m-d H:m:s'),
                        'updated_at' => now()->format('Y-m-d H:m:s')
                    ]);
                    $userCreated->syncRoles(Role::find(3));
                    $userCreated->save();

                    DB::commit();
                }
                catch(\Exception $e){
                    Log::channel('importacion_clientes')->info('  Fallo en la importación (Cliente CNT_TICKET NO existente). Se hace ROLLBACK.');
                    Log::channel('importacion_clientes')->info($e); 

                    DB::rollback();
                }
            }
            else{       // (EXISTE EN CNT_TICKETS)
                $siptizeUpdate = str_replace('/', '-', $apiCustomer['hutanUpdated']);
                $siptizeUpdate = Carbon::parse($siptizeUpdate);
                $bdUpdate = Carbon::parse($cnt_tickets_customer->updated_at)->format('Y-m-d');
                
                // Si la fecha de actualización de SIPTIZE no es igual a la fecha de actualización de BD actualizamos
                if($siptizeUpdate->eq($bdUpdate)){
                    Log::channel('importacion_clientes')->info('    No hay datos que actualizar en CNT_TICKET.'); 
                }
                else{
                    DB::beginTransaction();
                    try{
                        // cnt_tickets_customers
                        $cnt_tickets_customer->cif = $cif;
                        $cnt_tickets_customer->name = $customer_name;
                        $cnt_tickets_customer->alias = $customer_alias;
                        $cnt_tickets_customer->email = $email;
                        $cnt_tickets_customer->address = $apiCustomerDir['direccion'] ?? null;
                        $cnt_tickets_customer->town = $apiCustomerDir['ciudad'] ?? null;
                        $cnt_tickets_customer->postcode = $apiCustomerDir['codigoPostal'] ?? null;
                        $cnt_tickets_customer->phone = $apiCustomer['telefono'] ?? null;
                        $cnt_tickets_customer->is_active = $is_active;
                        $cnt_tickets_customer->updated_at = $siptizeUpdate;
                        $cnt_tickets_customer->update();

                        // cnt_tickets_users
                        $user = User::where('customer_id', $cnt_tickets_customer->customer_id)->first();
                        if($user->email == 'estherramirezmoya@hotmail.com' && $email != 'estherramirezmoya@hotmail.com'){
                            $user->name = $customer_name;
                            $user->username = $email;
                            $user->email = $email;
                            $user->is_active = $is_active;
                            $user->update();
                        }
                        else{
                            $user->name = $customer_name;
                            $user->is_active = $is_active;
                            $user->update();
                        }
    
                        Log::channel('importacion_clientes')->info('    Cliente actualizado en CNT_TICKET');
                        DB::commit();
                    }
                    catch(\Exception $e){
                        Log::channel('importacion_clientes')->info('  Fallo en la importación (Cliente existente en CNT_TICKET). Se hace ROLLBACK.');
                        Log::channel('importacion_clientes')->info($e); 
    
                        DB::rollback();
                    }   // Fin Try/Catch 

                }
            }   // fin tratamiento de cliente
        }   // fin foreach de todos los clientes

        Log::channel('importacion_clientes')->info('Proceso de importación de clientes finalizado.');
        Log::channel('importacion_clientes')->info('  ');
        Log::channel('importacion_clientes')->info('  ');
    }
}
