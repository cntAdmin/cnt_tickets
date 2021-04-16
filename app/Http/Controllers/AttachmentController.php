<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('attachments.index');
        }
        $attachments = Attachment::filterAttachments()->paginate();

        return response()->json([
            'attachments' => $attachments
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('attachments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // Validating incoming data
        $validator = Validator::make($req->all(), [
            'files' => ['required', 'array', 'max:20480'],
            'ticket_id' => ['required', 'numeric', 'exists:tickets,id']
        ]);
        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // INSERT DATA IF CORRECTLY VALIDATED
        // Store and get path
        if ($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT), $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                ]);
                $store = Ticket::find($req->ticket_id)->attachments()->save($attachment);
            }
        }

        return $store
            ? response()->json(['msg' => __('Fichero subido correctamente')], 201)
            : response()->json(['msg' => __('No se ha podido guardar el fichero correctamente.') ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        return response()->json([
            'attachment' => $attachment
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function edit(Attachment $attachment)
    {
        return response()->json([
            'attachment' => $attachment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Attachment $attachment)
    {
        // Validating incoming data
        $validator = Validator::make($req->all(), [
            'file' => ['required', 'file', 'size:20480']
        ]);
        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // INSERT DATA IF CORRECTLY VALIDATED
        // Store and get path
        $get_path = $req->file('file')
            ->store('media/' . $req->type . '/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT));
        $store = Attachment::create([
            'name' => $req->file('file')->getClientOriginalName(),
            'path' => $get_path
        ]);

        return $store
            ? response()->json(['msg' => __('Fichero subido correctamente')], 201)
            : response()->json(['msg' => __('No se ha podido guardar el fichero, por favor, contacte con el administrador.') ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        // if(auth()->user()->can('destroy', Attachment::class)) {
            Storage::delete($attachment->path);
            $delete = $attachment->delete();
        // }

        return $delete
            ? response()->json(['msg' => 'Fichero eliminado correctamente.'], 200)
            : response()->json(['msg' => 'El fichero no se ha podido eliminar, por favor, contacte con el administrador.'], 400);
    }

    public function download(Attachment $attachment)
    {
        return Storage::disk('public')->download($attachment->path);
    }
}
