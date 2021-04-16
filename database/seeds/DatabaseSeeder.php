<?php

use App\Models\TicketType;
use App\Ticket;
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
            // SON USUARIOS DE TEST
            UserSeeder::class,
            // DATOS REALES
            DepartmentSeeder::class,
            OriginTypeSeeder::class,
            PrioritySeeder::class,
            TicketTypeSeeder::class,
            TicketStatusSeeder::class,
            // DATOS CREADOS DE TEST
            DepartmentTypeSeeder::class,
            TicketSeeder::class,
            CommentSeeder::class,
            AttachmentSeeder::class,
        ]);
    }
}
