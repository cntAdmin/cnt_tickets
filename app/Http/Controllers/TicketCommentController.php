<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Str;

class TicketCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Ticket $ticket, Request $req)
    {
        return response()->json([ 'comments' => $ticket->comments ]);
        // return view('auth.login');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, Ticket $ticket): JsonResponse
    {
        $validator = Validator::make($req->all(), [
            'comment' => ['required', 'string'],
            'files' => ['nullable', 'array', 'max:5']
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors() ], 400);
        }

        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->user()->id,
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

        return response()->json(['msg' => 'Comentario creado correctamente.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket, Comment $comment): JsonResponse
    {
        return response()->json([ 'comments' => $ticket->comments ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket, Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket, Comment $comment)
    {
        //
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

}
