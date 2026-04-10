<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\MannequinCandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\ActionCode;
use App\Models\User;

class ContractController extends Controller
{
    public function store(Request $request, $candidateId)
    {
    $candidate = MannequinCandidate::findOrFail($candidateId);
    // ==================== BLOQUAGE BOOKEUSE ====================
    if (auth()->user()->role === 'bookeuse') {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return back()->withErrors(['general' => 'Admin introuvable']);
        }

        $request->validate([
            'contract' => 'required|mimes:pdf|max:5120'
        ]);

        // Stocker temporairement le fichier
        $contractPath = $request->file('contract')->store("temp/contracts", 'public');

        $code = rand(100000, 999999);

        ActionCode::create([
            'code'       => $code,
            'action'     => 'add_contract',
            'data'       => [
                'candidate_id' => $candidateId,
                'contract_path' => $contractPath,
                'user_id'       => auth()->id(),
            ],
            'user_id'    => auth()->id(),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::send('emails.verification-code', [
            'verificationCode' => $code,
            'action' => 'l\'ajout d\'un contrat',
        ], function ($message) use ($admin) {
            $message->to($admin->email)
                    ->subject('Code de validation - Bookeuse (Ajout Contrat)');
        });

        return redirect()->route('models.verification.show')
                         ->with('success', 'Code envoyé à l\'admin pour validation du contrat.');
    }
        try {
            $request->validate([
                'contract' => 'required|mimes:pdf|max:5120'
            ]);

            $candidate = MannequinCandidate::findOrFail($candidateId);

            // Delete old contract if exists
            if ($candidate->contract) {
                Storage::disk('public')->delete($candidate->contract->contract_url);
                $candidate->contract->delete();
            }

            $path = $request->file('contract')->store("models/{$candidate->user_id}/contracts", 'public');

            Contract::create([
                'candidate_id' => $candidateId,
                'user_id' => Auth::id(),
                'contract_url' => $path
            ]);

            return redirect()->back()->with('success', 'Contrat téléchargé avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors(['error' => 'Veuillez télécharger un fichier PDF valide (max 5MB)']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erreur lors du téléchargement du contrat: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, Contract $contract)
    {
        $request->validate([
            'status' => 'required|in:En attente,Actif,Inactif'
        ]);

        $statusMap = [
            'En attente' => 'pending',
            'Actif' => 'active',
            'Inactif' => 'inactive'
        ];

        $contract->update([
            'status' => $statusMap[$request->status]
        ]);

        return redirect()->back()->with('success', 'Statut du contrat mis à jour avec succès');
    }

    public function destroy($id)
    {
        try {
            // Check if user is admin
            if (!Auth::check() || !in_array(Auth::user()->role, ['jury', 'admin'])) {
                return redirect()->route('mainviews.forbidden');
            }

            $rating = Contract::findOrFail($id);
            $rating->delete();

            return back()->with(['success' => 'Document de contrat supprimée avec succès']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression du document de contrat'], 500);
        }
    }
}
