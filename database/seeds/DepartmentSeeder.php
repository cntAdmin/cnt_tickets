<?php

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['name' => 'Nuevas Contrataciones', 'code' => 'NC1', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['name' => 'Soporte Técnico', 'code' => 'SP1', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['name' => 'Otros', 'code' => 'OT1', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()]
        ];

        App\Models\Department::insert($departments);
    }
}
