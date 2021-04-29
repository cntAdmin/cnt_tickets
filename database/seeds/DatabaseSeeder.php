<?php

use App\Models\InvoiceableType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // CLIENTES REALES DE DOLIBARR
            CustomerSeeder::class,
            RoleSeeder::class,
            PermissionsSeeder::class,
            // SON USUARIOS DE TEST
            UserSeeder::class,
            // DATOS REALES
            DepartmentSeeder::class,
            OriginTypeSeeder::class,
            PrioritySeeder::class,
            TicketTypeSeeder::class,
            TicketStatusSeeder::class,
            WarrantySeeder::class,
            // DATOS CREADOS DE TEST
            DepartmentTypeSeeder::class,
            InvoiceableTypeSeeder::class,
            TicketSeeder::class,
            CommentSeeder::class,
            AttachmentSeeder::class,
        ]);
    }
}
