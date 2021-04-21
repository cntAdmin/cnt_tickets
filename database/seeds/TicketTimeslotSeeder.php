<?php

use App\Models\Ticket;
use App\Models\TicketTimeslot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class TicketTimeslotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(App::environment('local')) {
            factory(TicketTimeslot::class, 50)->create()->each(function(TicketTimeslot $timeslot) {
                $timeslot->ticket()->associate(Ticket::where('ticket_status_id', 2)->inRandomOrder()->first());
                $timeslot->save();
            });
        }
    }
}
