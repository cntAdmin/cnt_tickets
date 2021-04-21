<?php

use App\Models\Warranty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;

class WarrantySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(AppEnv::environment('local')) {
            factory(Warranty::class, 5)->create();
        } else {
            $waranties = [
                ['name' => 'Total', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Parcial', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()]
            ];
            Warranty::insert($waranties);
        }
        
    }
}
