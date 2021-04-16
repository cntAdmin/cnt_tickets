<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (AppEnv::environment('local')) {
            factory(App\Models\Priority::class, 5)->create();
        } else {
            $priorities = [
                ['name' => 'Baja', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Media', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Alta', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ];
            App\Models\Priority::insert($priorities);
        }
    }
}
