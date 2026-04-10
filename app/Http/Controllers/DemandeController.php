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
        // Admin voit → marquer seen_by_admin = 1 SEULEMENT, ne pas toucher status
        Demande::whereIn('id', $ids)
            ->where('seen_by_admin', 0)
            ->update(['seen_by_admin' => 1]);
    } else {
        // bookeuse voit → marquer status = 1 SEULEMENT, ne pas toucher seen_by_admin
        Demande::whereIn('id', $ids)
            ->where('status', 0)
            ->update(['status' => 1]);
    }

    return view('demandes.view', compact('demandes'));
}
    public function destroy($id)
{
    $demande = Demande::findOrFail($id);
    $demande->delete();
    return redirect()->route('demandes.view')->with('success', 'Demande supprimée avec succès.');
}
}