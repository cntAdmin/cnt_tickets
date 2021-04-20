<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AttachmentPolicy
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
     * @param  \App\Attachment  $attachment
     * @return mixed
     */
    public function view(User $user, Attachment $attachment)
    {
        $attachments = [];
        User::find(101)->tickets()->withoutGlobalScopes()->each(function($ticket) use (&$attachments) {
            if($ticket->attachments()->count() > 0){
                $ticket->attachments()->pluck('attachments.id', 'attachments.id')->each(function($attachment) use (&$attachments) {
                    $attachments[$attachment] = $attachment;
                });
            }
        });
        if(in_array($attachment->id, $attachments)) {
            return Response::allow();
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
        return $user
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Attachment  $attachment
     * @return mixed
     */
    public function update(User $user, Attachment $attachment)
    {
        return $user->hasRole(1)
            ? Response::allow()
            : Response::deny('No tienes permisos para acceder.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Attachment  $attachment
     * @return mixed
     */
    public function delete(User $user, Attachment $attachment)
    {
        if($user->roles[0]->id === 1) return Response::allow();
        
        $attachments = [];
        User::find(101)->tickets()->withoutGlobalScopes()->each(function($ticket) use (&$attachments) {
            if($ticket->attachments()->count() > 0){
                $ticket->attachments()->pluck('attachments.id', 'attachments.id')->each(function($attachment) use (&$attachments) {
                    $attachments[$attachment] = $attachment;
                });
            }
        });
        if(in_array($attachment->id, $attachments)) {
            return Response::allow();
        } else {
            return Response::deny('No tienes permisos para acceder.');
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Attachment  $attachment
     * @return mixed
     */
    public function restore(User $user, Attachment $attachment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Attachment  $attachment
     * @return mixed
     */
    public function forceDelete(User $user, Attachment $attachment)
    {
        //
    }
}
