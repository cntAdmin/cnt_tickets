<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;

class DepartmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (AppEnv::environment('local')) {
            factory(App\Models\DepartmentType::class, 10)->create()->each(function($dt) {
                $dt->department()->associate(App\Models\Department::inRandomOrder()->first());
                $dt->save();
            });
        }
    }
}
