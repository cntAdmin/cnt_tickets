<?php

use App\Models\TicketStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ticket_statuses = [
            ['name' => 'Nuevo', 'icon' => 'envelope-open', 'color' => 'primary', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['name' => 'Abierto', 'icon' => 'plus-circle', 'color' => 'info', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ['name' => 'Resuelto', 'icon' => 'check-circle', 'color' => 'success', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
        ];

        TicketStatus::insert($ticket_statuses);
    }
}
