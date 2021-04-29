<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    private $comment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(!$this->comment->customer) {
            $sendTo = [$this->comment->user->email];
        } else {
            if( $this->comment->ticket->user->email !== $this->comment->ticket->customer->email ) {
                $sentTo = [$this->comment->ticket->user->email, $this->comment->ticket->customer->email];
            } else {
                $sentTo = [$this->comment->ticket->user->email];
            }
        }

        return $this->to($sendTo)->view('mails.ticket_comment')
            ->with([
                'comment' => $this->comment , 'ticket' => $this->comment->ticket
            ]);
    }
}
