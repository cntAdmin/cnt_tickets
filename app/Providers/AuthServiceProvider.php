<?php

namespace App\Providers;

use App\Http\Controllers\CustomerTicketController;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Ticket' => 'App\Policies\TicketPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Customer' => 'App\Policies\CustomerPolicy',
        'App\Models\Attachment' => 'App\Policies\AttachmentPolicy',
        'App\Models\OriginType' => 'App\Policies\OriginTypePolicy',
        'App\Models\Department' => 'App\Policies\DepartmentPolicy',
        'App\Models\DepartmentType' => 'App\Policies\DepartmentTypePolicy',
        'App\Models\TicketStatus' => 'App\Policies\TicketStatusPolicy',
        'App\Models\Priority' => 'App\Policies\PriorityPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user) {
            return $user->hasRole(1) ? true : null;
        });
    }
}
