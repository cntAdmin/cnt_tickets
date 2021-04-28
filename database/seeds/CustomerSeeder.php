<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (AppEnv::environment('local')) {
            factory(App\Models\Customer::class, 50)->create();
        } else {
            $customers = [];
            App\Models\Dolibarr\Customer::get()->each(function($customer) use(&$customers) {
                $customers[] = [
                    'id' => $customer->id,
                    'cif' => Str::upper(Str::replaceArray('-', [''], $customer->siren)),
                    'name' => $customer->nom,
                    'alias' => $customer->name_alias,
                    'email' => $customer->email,
                    'address' => $customer->address,
                    'town' => $customer->town,
                    'postcode' => $customer->zip,
                    'phone' => $customer->phone,
                    'fax' => $customer->fax,
                    'is_active' => $customer->status,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            });
            App\Models\Customer::insert($customers);
        }
    }
}
