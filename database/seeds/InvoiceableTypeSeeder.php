<?php

use App\Models\InvoiceableType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class InvoiceableTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(App::environment('local')) {
            factory(InvoiceableType::class, 5)->create();
        } else {
            $invoiceable_types = [
                ['name' => 'A facturar', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Facturado', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ];

            InvoiceableType::insert($invoiceable_types);
        }
    }
}
