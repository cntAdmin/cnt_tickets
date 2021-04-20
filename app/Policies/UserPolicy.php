<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        switch ($user->roles[0]->id) {
            case 2: // AGENT
            case 4: // USER
                return $user->id === $model->id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para acceder aqui.');
                break;
            case 3: // CUSTOMER
                return $user->customer->id === $model->customer_id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para acceder aqui.');
                break;
            
            default:
                return Response::deny('No tienes permisos para acceder aqui.');
                break;
        }
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
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        switch ($user->roles[0]->id) {
            case 2: // AGENT
            case 4: // USER
                return $user->id === $model->id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para acceder aqui.');
                break;
            case 3: // CUSTOMER
                return $user->customer->id === $model->customer_id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para acceder aqui.');
                break;
            
            default:
                return Response::deny('No tienes permisos para acceder aqui.');
                break;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        switch ($user->roles[0]->id) {
            case 2: // AGENT
            case 4: // USER
                return $user->id === $model->id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para acceder aqui.');
                break;
            case 3: // CUSTOMER
                return $user->customer->id === $model->customer_id
                    ? Response::allow()
                    : Response::deny('No tienes permisos para acceder aqui.');
                break;
            
            default:
                return Response::deny('No tienes permisos para acceder aqui.');
                break;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
