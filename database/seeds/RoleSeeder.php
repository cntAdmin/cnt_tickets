<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Admin', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['name' => 'Agente', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['name' => 'Cliente', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['name' => 'Usuario', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
        ];
        Role::insert($roles);
    }
}
