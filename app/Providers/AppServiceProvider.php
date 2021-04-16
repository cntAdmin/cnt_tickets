<?php

namespace App\Providers;

use App\Observers\TicketCommentObserver;
use App\Observers\TicketObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Comment::observe(TicketCommentObserver::class);
        \App\Models\Ticket::observe(TicketObserver::class);

    }
}
