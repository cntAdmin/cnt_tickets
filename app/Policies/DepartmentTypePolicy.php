<?php

namespace App\Policies;

use App\Models\DepartmentType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DepartmentTypePolicy
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
        return $user->roles[0]->id === 1
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\DepartmentType  $departmentType
     * @return mixed
     */
    public function view(User $user, DepartmentType $departmentType)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles[0]->id === 1
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\DepartmentType  $departmentType
     * @return mixed
     */
    public function update(User $user, DepartmentType $departmentType)
    {
        return $user->roles[0]->id === 1
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\DepartmentType  $departmentType
     * @return mixed
     */
    public function delete(User $user, DepartmentType $departmentType)
    {
        return $user->roles[0]->id === 1
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\DepartmentType  $departmentType
     * @return mixed
     */
    public function restore(User $user, DepartmentType $departmentType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\DepartmentType  $departmentType
     * @return mixed
     */
    public function forceDelete(User $user, DepartmentType $departmentType)
    {
        //
    }
}
