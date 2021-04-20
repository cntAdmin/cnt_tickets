<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
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
        return $user->hasRole(1)
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function view(User $user, Customer $customer)
    {
        return $user->customer_id === $customer->id
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole(1)
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function update(User $user, Customer $customer)
    {
        switch ($user->roles[0]->id) {
            case 3:
                return $user->customer_id === $customer->id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para editar este cliente.');
                break;

            default:
                return Response::deny('No puedes editar este cliente, no tienes suficientes permisos.');
                break;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function delete(User $user, Customer $customer)
    {
        return ($user->hasRole(1) || ($user->hasRole(3) && $user->customer_id === $customer->id))
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function restore(User $user, Customer $customer)
    {
        return $user->hasRole(1)
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function forceDelete(User $user, Customer $customer)
    {
        return $user->hasRole(1)
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }
}
