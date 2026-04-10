<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'candidate_id' => 'required|exists:mannequin_candidates,id',
            'comment_content' => 'required|string|max:1000'
        ]);

        try {
            $comment = Comment::create([
                'user_id' => Auth::id(),
                'candidate_id' => $validated['candidate_id'],
                'comment_content' => $validated['comment_content']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre commentaire a été ajouté avec succès',
                'comment' => $comment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur d\'ajout de votre commentaire'
            ], 500);
        }
    }
}
