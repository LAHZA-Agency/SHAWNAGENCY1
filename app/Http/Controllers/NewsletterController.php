<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsletterController extends Controller
{
    public function view(Request $request)
    {
        $response = Http::withHeaders([
            'api-key' => config('services.brevo.key'),
            'accept' => 'application/json',
        ])->get('https://api.brevo.com/v3/contacts', [
            'limit' => 50
        ]);

        $data = $response->json();

        $contacts = $data['contacts'] ?? [];

        $contacts = array_values(array_filter($contacts, function ($c) {
            return isset($c['email']) && !empty($c['email']);
        }));

        // SEARCH
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $contacts = array_filter($contacts, function ($contact) use ($search) {
                return str_contains(strtolower($contact['email']), $search)
                    || str_contains(strtolower($contact['attributes']['FIRSTNAME'] ?? ''), $search);
            });
        }

        // DATE FILTER
        if ($request->filled('date')) {
            $date = $request->date;

            $contacts = array_filter($contacts, function ($contact) use ($date) {
                return isset($contact['createdAt']) &&
                    \Carbon\Carbon::parse($contact['createdAt'])->format('Y-m-d') === $date;
            });
        }

        $contacts = array_values($contacts);

        return view('newsletter.view', compact('contacts'));
    }
    public function destroy($email)
{
    // Protection : seul l'admin peut supprimer
    if (!auth()->check() || auth()->user()->role !== 'admin') {

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'error' => 'Vous n\'avez pas l\'autorisation de supprimer ce contact newsletter.'
            ], 403);
        }

        return back()->withErrors([
            'error' => 'Action non autorisée. Seul l\'administrateur peut supprimer un contact newsletter.'
        ]);
    }

    try {

        Http::withHeaders([
            'api-key' => config('services.brevo.key'),
        ])->delete("https://api.brevo.com/v3/contacts/$email");

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true

            ]);
        }

        return back()->with('success');

    } catch (\Exception $e) {

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'error' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }

        return back()->withErrors([
            'error' => 'Erreur lors de la suppression du contact newsletter.'
        ]);
    }
}
}
