<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (AppEnv::environment('local')) {
            factory(App\Models\Ticket::class, 100)->create()->each(function($ticket) {
                $ticket->customer()->associate(App\Models\Customer::inRandomOrder()->first());
                $ticket->user()->associate(App\Models\User::inRandomOrder()->first());
                $ticket->agent()->associate(App\Models\User::role(2)->inRandomOrder()->first());
                $ticket->priority()->associate(App\Models\Priority::inRandomOrder()->first());
                $ticket->origin_type()->associate(App\Models\OriginType::inRandomOrder()->first());
                $ticket->ticket_type()->associate(App\Models\TicketType::inRandomOrder()->first());
                $ticket->ticket_status()->associate(App\Models\TicketStatus::inRandomOrder()->first());
                $ticket->created_by_user()->associate(App\Models\User::inRandomOrder()->first());
                $ticket->warranty()->associate(App\Models\Warranty::inRandomOrder()->first());
                $ticket->invoiceable_type()->associate(App\Models\InvoiceableType::inRandomOrder()->first());
                
                $ticket->save();
            });
        }
    }
}
