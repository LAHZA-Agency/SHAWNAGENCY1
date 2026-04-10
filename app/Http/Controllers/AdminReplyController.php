<?php

namespace App\Http\Controllers;

use App\Models\AdminReply;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminReplyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'reply_content' => 'required|string'
        ]);

        $reply = AdminReply::create([
            'comment_id' => $validated['comment_id'],
            'admin_id' => Auth::id(),
            'reply_content' => $validated['reply_content']
        ]);

        $comment = Comment::with('user')->findOrFail($validated['comment_id']);
        $user = $comment->user;
        Mail::send('emails.admin_reply', [
            'name' => $user->name,
            'reply_content' => $validated['reply_content'],
            'comment_content' => $comment->comment_content,
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Réponse de l\'administrateur à votre commentaire');
        });

        return response()->json([
            'success' => true,
            'message' => 'Réponse ajoutée avec succès',
            'reply' => $reply->load('admin')
        ]);
    }

    public function destroy($id)
    {
        $reply = AdminReply::findOrFail($id);
        $reply->delete();

        return response()->json([
            'success' => true,
            'message' => 'Réponse supprimée avec succès'
        ]);
    }
}
