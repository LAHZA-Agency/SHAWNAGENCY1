<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;

class DemandeController extends Controller
{
    public function view(Request $request)
    {
        $query = Demande::with('mannequinCandidate')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $demandes = $query->paginate(10)->withQueryString();
        $ids = $demandes->pluck('id');

        if (auth()->user()->role === 'admin') {
            Demande::whereIn('id', $ids)
                ->where('seen_by_admin', 0)
                ->update(['seen_by_admin' => 1]);
        } else {
            Demande::whereIn('id', $ids)
                ->where('status', 0)
                ->update(['status' => 1]);
        }

        return view('demandes.view', compact('demandes'));
    }
    public function destroy($id)
{
    $demande = Demande::findOrFail($id);

    if (!auth()->check() || auth()->user()->role !== 'admin') {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'error' => 'Vous n\'avez pas l\'autorisation de supprimer cette demande.'
            ], 403);
        }

        return redirect()->route('demandes.view')
                         ->withErrors(['error' => 'Action non autorisée. Seul l\'administrateur peut supprimer une demande.']);
    }

    try {
        $demande->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true

            ]);
        }

        return redirect()->route('demandes.view')
                         ->with('success');
    }
    catch (\Exception $e) {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'error' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }

        return redirect()->route('demandes.view')
                         ->withErrors(['error' => 'Erreur lors de la suppression de la demande.']);
    }
    }
}
