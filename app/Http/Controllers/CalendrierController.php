<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MannequinCandidate;

class CalendrierController extends Controller
{
     public function View() {
        return view('calendrier.view');

    }

   public function getModelAvailability(Request $request)
{
    $start = $request->query('start'); 
    $end = $request->query('end');    

    $startFilter = $request->query('disponibilite_debut');
    $endFilter = $request->query('disponibilite_fin');

    $today = now()->startOfDay();

    $availabilities = MannequinCandidate::where(function($q) use ($start, $end, $startFilter, $endFilter, $today) {
        
        // Toujours limiter au mois affiché
        $q->where(function($q1) use ($start, $end) {
            $q1->whereBetween('disponibilite_debut', [$start, $end])
               ->orWhereBetween('disponibilite_fin', [$start, $end])
               ->orWhere(function($q2) use ($start, $end) {
                   $q2->where('disponibilite_debut', '<=', $start)
                      ->where('disponibilite_fin', '>=', $end);
               });
        });

        // 🔥 Si filtre utilisateur fourni, garder tous les modèles qui **chevauchent la plage**
        if ($startFilter && $endFilter) {
            $q->where(function($q2) use ($startFilter, $endFilter) {
                $q2->where('disponibilite_debut', '<=', $endFilter)
                   ->where('disponibilite_fin', '>=', $startFilter);
            });
        }

        // Toujours ignorer les disponibilités terminées
        $q->where('disponibilite_fin', '>=', $today);

    })->get();

    $events = $availabilities->map(function($candidate) {
        return [
            'title' => $candidate->user->name ?? 'Nom inconnu',
            'start' => $candidate->disponibilite_debut->format('Y-m-d'),
            'end' => $candidate->disponibilite_fin->format('Y-m-d'),
            'color' => 'green',
        ];
    });

    return response()->json($events);
}

    public function search(Request $request)
    {
    return view('calendrier.view', [
        'disponibilite_debut' => $request->disponibilite_debut,
        'disponibilite_fin' => $request->disponibilite_fin,
    ]);
    }

}
