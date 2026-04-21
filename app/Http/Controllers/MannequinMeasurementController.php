<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\MannequinCandidate;
use App\Models\MannequinMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\ActionCode;
use App\Models\User;


class MannequinMeasurementController extends Controller
{
    public function create($model_id)
    {
        $candidate = MannequinCandidate::findOrFail($model_id);
        return view('mainviews.add-model-measurements', compact('candidate'));
    }

   public function store(Request $request, $candidateId)
    {
        $candidate = MannequinCandidate::findOrFail($candidateId);

        $validated = $request->validate([
            'head_circumference'       => 'nullable|numeric',
            'neck_base_circumference'  => 'nullable|numeric',
            'shoulder_length'          => 'nullable|numeric',
            'arm_length'               => 'nullable|numeric',
            'front_width'              => 'nullable|numeric',
            'chest_circumference'      => 'nullable|numeric',
            'waist_circumference'      => 'nullable|numeric',
            'small_hips_circumference' => 'nullable|numeric',
            'hips_circumference'       => 'nullable|numeric',
            'thigh_circumference'      => 'nullable|numeric',
            'knee_circumference'       => 'nullable|numeric',
            'calf_circumference'       => 'nullable|numeric',
            'ankle_circumference'      => 'nullable|numeric',
            'upper_arm_circumference'  => 'nullable|numeric',
            'elbow'                    => 'nullable|numeric',
            'forearm_circumference'    => 'nullable|numeric',
            'wrist_size'               => 'nullable|numeric',
            'wrist_to_elbow'           => 'nullable|numeric',
            'inseam_length'            => 'nullable|numeric',
            'knee_height'              => 'nullable|numeric',
            'side_height'              => 'nullable|numeric',
            'total_height'             => 'nullable|numeric',
            'comment'                  => 'nullable|string|max:1000',
            'pointure'                 => 'nullable|numeric',
            'confection'               => 'nullable|string|max:255',
            'poids'                    => 'nullable|numeric',
            'tour_de_hanches'          => 'nullable|numeric',
            'belt_circumference'       => 'nullable|numeric',
        ]);


        if (auth()->user()->role === 'bookeuse') {
            $admin = User::where('role', 'admin')->first();

            if (!$admin) {
                return back()->withErrors(['general' => 'Admin introuvable']);
            }

            $code = rand(100000, 999999);

            ActionCode::create([
                'code'       => $code,
                'action'     => 'add_measurements',
                'data'       => array_merge($validated, [
                    'candidate_id' => $candidateId,
                    'user_id'      => auth()->id(),
                    'model_user_id' => $candidate->user_id,
                ]),
                'user_id'    => auth()->id(),
                'expires_at' => now()->addMinutes(10),
            ]);

            Mail::send('emails.verification-code', [
                'verificationCode' => $code,
                'action'           => "l'ajout des mensurations",
            ], function ($message) use ($admin) {
                $message->to($admin->email)
                        ->subject('Code de validation - Bookeuse (Mensurations)');
            });

            return redirect()->route('models.verification.show')
                             ->with('success');
        }

        try {
            $validated['candidate_id'] = $candidate->id;
            $validated['user_id']      = Auth::id();

            MannequinMeasurement::create($validated);

            if (!empty($validated['comment'])) {
                Comment::create([
                    'user_id'         => Auth::id(),
                    'candidate_id'    => $candidate->id,
                    'comment_content' => $validated['comment'],
                ]);
            }

            if (Auth::user()->role === 'admin') {
                return back()->with('success');
            }

            return redirect()->route('dashboard')
                             ->with('success');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $measurement = MannequinMeasurement::findOrFail($id);
        $measurement->delete();

        return back()->with('success');
    }


   public function updateMeasurements(Request $request, $id)
{
    $measurement = MannequinMeasurement::findOrFail($id);

    $validated = $request->validate([
        'head_circumference'       => 'nullable|numeric',
        'neck_base_circumference'  => 'nullable|numeric',
        'shoulder_length'          => 'nullable|numeric',
        'arm_length'               => 'nullable|numeric',
        'front_width'              => 'nullable|numeric',
        'chest_circumference'      => 'nullable|numeric',
        'waist_circumference'      => 'nullable|numeric',
        'small_hips_circumference' => 'nullable|numeric',
        'hips_circumference'       => 'nullable|numeric',
        'thigh_circumference'      => 'nullable|numeric',
        'knee_circumference'       => 'nullable|numeric',
        'calf_circumference'       => 'nullable|numeric',
        'ankle_circumference'      => 'nullable|numeric',
        'upper_arm_circumference'  => 'nullable|numeric',
        'elbow'                    => 'nullable|numeric',
        'forearm_circumference'    => 'nullable|numeric',
        'wrist_size'               => 'nullable|numeric',
        'wrist_to_elbow'           => 'nullable|numeric',
        'inseam_length'            => 'nullable|numeric',
        'knee_height'              => 'nullable|numeric',
        'side_height'              => 'nullable|numeric',
        'total_height'             => 'nullable|numeric',
        'comment'                  => 'nullable|string|max:1000',
        'pointure'                 => 'nullable|numeric',
        'confection'               => 'nullable|string|max:255',
        'poids'                    => 'nullable|numeric',
        'tour_de_hanches'          => 'nullable|numeric',
        'belt_circumference'       => 'nullable|numeric',
    ]);


    if (auth()->user()->role === 'bookeuse') {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->withErrors(['general' => 'Admin introuvable']);
        }

        $code = rand(100000, 999999);

        ActionCode::create([
        'code'       => $code,
        'action'     => 'update_measurements',
        'data'       => array_merge(
            $validated,
            [
                'measurement_id' => (int) $id,
                'user_id'        => auth()->id(),
                'model_user_id'  => $measurement->candidate->user_id,
            ]
        ),
        'user_id'    => auth()->id(),
        'expires_at' => now()->addMinutes(10),
    ]);

        Mail::send('emails.verification-code', [
            'verificationCode' => $code,
            'action'           => 'la mise à jour des mensurations',
        ], function ($message) use ($admin) {
            $message->to($admin->email)
                    ->subject('Code de validation - Bookeuse (Mise à jour Mensurations)');
        });

        return redirect()->route('models.verification.show')
                         ->with('success');
    }

    try {
        $measurement->update($validated);
        return back()->with('success');
    } catch (\Exception $e) {
        Log::error('Error updating measurements: ' . $e->getMessage());
        return back()
            ->withErrors(['error' => 'Erreur : ' . $e->getMessage()])
            ->withInput();
    }
}
}
