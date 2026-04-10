<?php

namespace App\Http\Controllers;

use App\Models\ModelRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModelRatingController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Check if user is jury or admin
            if (!Auth::check() || !in_array(Auth::user()->role, ['jury', 'admin'])) {
                return redirect()->route('mainviews.forbidden');
            }

            // Validate request data
            $validated = $request->validate([
                'candidate_id' => 'required|exists:mannequin_candidates,id',
                'rating' => 'required|integer|min:0|max:20',
                'comment' => 'nullable|string|max:1000'
            ]);

            // Check if rating already exists
            $existingRating = ModelRating::where('judge_id', Auth::id())
                ->where('candidate_id', $validated['candidate_id'])
                ->first();

            if ($existingRating) {
                return back()->withErrors(['error' => 'Vous avez déjà noté ce mannequin']);
            }

            // Create new rating
            ModelRating::create([
                'judge_id' => Auth::id(),
                'candidate_id' => $validated['candidate_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null
            ]);

            return back()->with('success', 'Note ajoutée avec succès');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'ajout de la note']);
        }
    }

    public function destroy($id)
    {
        try {
            // Check if user is admin
            if (!Auth::check() || !in_array(Auth::user()->role, ['jury', 'admin'])) {
                return redirect()->route('mainviews.forbidden');
            }

            $rating = ModelRating::findOrFail($id);
            $rating->delete();

            return back()->with(['success' => 'Note supprimée avec succès']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression de la note'], 500);
        }
    }
}
