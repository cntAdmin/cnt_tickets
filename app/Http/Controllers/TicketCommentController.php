<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Str;

class TicketCommentController extends Controller
{
    public function index(Ticket $ticket, Request $req)
    {
        return response()->json([ 'comments' => $ticket->comments ]);
    }
    
    public function store(Request $req, Ticket $ticket): JsonResponse
    {
        $validator = Validator::make($req->all(), [
            'comment' => ['required', 'string'],
            'files' => ['nullable', 'array', 'max:5']
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors() ], 422);
        }

        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->user()->id ?? $ticket->user->id,
            'description' => $req->comment,
            '_token' => $this->createToken()
        ]);
        
        if ($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT), $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                ]);
                $comment->attachments()->save($attachment);
            }
        }

        return $comment 
            ? response()->json(['msg' => 'Comentario creado correctamente.'], 200)
            : response()->json(['msg' => 'No se ha podido crear el comentario, por favor, contacte con el administrador.'], 400);
    }

    public function show(Ticket $ticket, Comment $comment): JsonResponse
    {
        return response()->json([ 'comments' => $ticket->comments ]);
    }

    public function createToken()
    {
        $token = Str::uuid();
        $exists = Comment::where('_token', $token)->first();

        if(!$exists) {
            return $token;
        } else {
            $this->createToken();
        }
    }

    public function getTicketThroughToken(Comment $comment)
    {
        if($comment->created_at->diffInMinutes(now()) > $comment->expires_in) {
            return abort(403);
        } else {
            return view('tickets.show_annonymous')
                ->with([
                    'ticket' => $comment->ticket
                                ->load(['comments', 'ticket_type', 'agent', 'department_type', 'priority', 'origin_type', 'warranty'])
                ]);
        }
    }

}
