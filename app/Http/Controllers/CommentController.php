<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->update([
            'deleted_by' => auth()->user()->id
        ]);
        $delete = $comment->delete();

        return isset($delete) && $delete == true
            ? response()->json([ 'msg' => 'Comentario borrado correctamente.'])
            : response()->json([ 'msg' => 'No se ha podido borrar el comentario, por favor, contacte con el administrador.'], 400);

    }
}
