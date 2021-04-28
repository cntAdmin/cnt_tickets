<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user
            ? Response::allow()
            : Response::deny('No tienes permisos para listar tickets.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ticket  $ticket
     * @return mixed
     */
    public function view(User $user, Ticket $ticket)
    {
        switch ($user->roles[0]->id) {
            case 2:
                return $user->id === $ticket->created_by || $user->id === $ticket->agent_id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para ver este ticket.');
                break;
            case 3:
                return $user->customer_id === $ticket->customer_id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para ver este ticket.');
                break;
            case 4:
                return $user->customer_id === $ticket->customer_id && $user->id === $ticket->user_id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para ver este ticket.');

                break;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, ?Customer $customer = null)
    {
        switch ($user->roles[0]->id) {
            case 2: // AGENTE
                return true;
                break;
            case 3: // CUSTOMER
                return in_array(request()->path(), ["ticket-type/1/ticket/crear", "ticket-type/2/ticket/crear"]) || $user->customer_id === $customer->id;
                break;
            case 4: // USER
                return true;
                break;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ticket  $ticket
     * @return mixed
     */
    public function update(User $user, Ticket $ticket)
    {
        switch ($user->roles[0]->id) {
            case 2:
                return $user->id === $ticket->created_by || $user->id === $ticket->agent_id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para ver este ticket.');
                break;
            default:
                return Response::deny('No tienes permisos para ver este ticket.');
                break;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ticket  $ticket
     * @return mixed
     */
    public function delete(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->created_by
            ? Response::allow()
            : Response::deny('No tienes permisos para editar este ticket');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function restore(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->created_by
            ? Response::allow()
            : Response::deny('No tienes permisos para editar este ticket');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ticket  $ticket
     * @return mixed
     */
    public function forceDelete(User $user, Ticket $ticket)
    {
        return $user->hasRole(1);
    }
}
