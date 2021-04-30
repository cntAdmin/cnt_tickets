<?php

namespace App\Observers;

use App\Mail\TicketCommentMail;
use App\Models\Comment;
use App\Models\TicketStatus;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class TicketCommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $admin_roles = [1, 2];
        if (Arr::exists($admin_roles,  $comment->user->roles[0]->id)) {
            $comment->ticket()->update([
                'read_by_admin' => true,
                'ticket_status_id' => TicketStatus::where('name', 'LIKE', '%abierto%')->first()->id ?: $comment->ticket->ticket_status->id ?: null
            ]);

            if ($comment->ticket->ticket_type->id === 1) {
                if (!$comment->customer) {
                    $sendTo = [$comment->user->email];
                } else {
                    if ($comment->ticket->user->email !== $comment->ticket->customer->email) {
                        $sendTo = [$comment->ticket->user->email, $comment->ticket->customer->email];
                    } else {
                        $sendTo = [$comment->ticket->user->email];
                    }
                }
                Mail::to($sendTo)->send(new TicketCommentMail($comment));
            }
        } else {
            $comment->ticket()->update([
                'read_by_admin' => false
            ]);
        }
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "restored" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "force deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }
}
