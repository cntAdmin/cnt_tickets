<?php

use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $ticket_types = [
            ['name' => 'Ticket', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['name' => 'Parte de Trabajo', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()]
        ];

        App\Models\TicketType::insert($ticket_types);
        
    }
}
