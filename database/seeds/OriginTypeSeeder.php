<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;

class OriginTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (AppEnv::environment('local')) {
            factory(App\Models\OriginType::class, 5)->create();
        } else {
            $origins = [
                ['name' => 'Llamada Telefónica', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Email', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ];

            App\Models\OriginType::insert($origins);
        }
    }
}
