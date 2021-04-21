<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_permissions = [
            ['id' => 1, 'name'=> 'ticket.create', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 2, 'name'=> 'ticket.show', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 3, 'name'=> 'ticket.update', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 4, 'name'=> 'ticket.destroy', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 5, 'name'=> 'customer.create', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 6, 'name'=> 'customer.show', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 7, 'name'=> 'customer.update', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 8, 'name'=> 'customer.destroy', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 9, 'name'=> 'user.create', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 10, 'name'=> 'user.show', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 11, 'name'=> 'user.update', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 12, 'name'=> 'user.destroy', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 13, 'name'=> 'ticket_status.create', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 14, 'name'=> 'ticket_status.show', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 15, 'name'=> 'ticket_status.update', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 16, 'name'=> 'ticket_status.destroy', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 17, 'name'=> 'department.create', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 18, 'name'=> 'department.show', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 19, 'name'=> 'department.update', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 20, 'name'=> 'department.destroy', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 21, 'name'=> 'department_type.create', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 22, 'name'=> 'department_type.show', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 23, 'name'=> 'department_type.update', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 24, 'name'=> 'department_type.destroy', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 25, 'name'=> 'origin_type.create', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 26, 'name'=> 'origin_type.show', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 27, 'name'=> 'origin_type.update', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['id' => 28, 'name'=> 'origin_type.destroy', 'guard_name' => 'web', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
        ];
        Permission::insert($all_permissions);
        Spatie\Permission\Models\Role::find(1)->syncPermissions(Permission::all());
        $agent_permissions = [1, 2, 3, 4, 6, 9, 10, 11, 12, 13, 18, 22, 26];
        $agent_sync_permissions = Spatie\Permission\Models\Role::find(2)->syncPermissions($agent_permissions);
        $customer_permissions = [2, 6, 7, 10, 11, 14, 18, 22, 26];
        $customer_sync_permissions = Spatie\Permission\Models\Role::find(3)->syncPermissions($customer_permissions);
        $user_permissions = [2, 10, 11, 14, 18, 22, 26];
        $user_sync_permissions = Spatie\Permission\Models\Role::find(4)->syncPermissions($customer_permissions);

    }
}
