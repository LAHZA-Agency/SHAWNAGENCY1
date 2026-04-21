<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\MannequinCandidate;
use App\Models\Demande;
use App\Models\MannequinMeasurement;
use App\Models\Contract;
use App\Models\MannequinImage;
use App\Models\User;
use App\Models\ActionCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ModelController extends Controller
{

    public function index()
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'Candidate') {
            return redirect()->route('model.profile', ['id' => $currentUser->id]);
        }

        $query = User::with('mannequinCandidate')
    ->where('role', 'Candidate')
    ->whereHas('mannequinCandidate')->orderBy('created_at', 'desc');


        if ($currentUser->role === 'accueillant') {
            $query = $query->whereHas('mannequinCandidate', function ($q) {
                $q->where('status_model', 'pending')->where('verified', 'pending');
            });
        } else if ($currentUser->role === 'styliste') {
            $query->whereHas('mannequinCandidate', function ($q) {
                $q->where('status_model', 'approved')
                    ->where('verified', 'pending')
                    ->whereDoesntHave('measurements');
            });
        } else if ($currentUser->role === 'jury') {
            $query->whereHas('mannequinCandidate', function ($q) {
                $q->where('status_model', 'approved')
                    ->where('verified', 'pending')
                    ->whereHas('measurements');
            });
        } else if (!$currentUser->isAdmin()) {
        $query = $query->whereHas('mannequinCandidate', function ($q) {
        $q->where('status_model', 'approved')->where('verified', 'pending');
        });
}
        $models = $query->paginate(50);

        $thisMonth = now();
        $lastMonth = now()->subMonth();

        $thisMonthWeeks = collect([
            $thisMonth->copy()->startOfMonth()->startOfWeek(),
            $thisMonth->copy()->startOfMonth()->addWeek()->startOfWeek(),
            $thisMonth->copy()->startOfMonth()->addWeeks(2)->startOfWeek(),
            $thisMonth->copy()->startOfMonth()->addWeeks(3)->startOfWeek(),
            $thisMonth->copy()->endOfMonth()
        ]);

        $lastMonthWeeks = collect([
            $lastMonth->copy()->startOfMonth()->startOfWeek(),
            $lastMonth->copy()->startOfMonth()->addWeek()->startOfWeek(),
            $lastMonth->copy()->startOfMonth()->addWeeks(2)->startOfWeek(),
            $lastMonth->copy()->startOfMonth()->addWeeks(3)->startOfWeek(),
            $lastMonth->copy()->endOfMonth()
        ]);

        $thisMonthData = [];
        $lastMonthData = [];

        for ($i = 0; $i < 4; $i++) {
            $thisMonthData[] = User::where('role', 'Candidate')
                ->whereBetween('created_at', [
                    $thisMonthWeeks[$i],
                    $thisMonthWeeks[$i + 1]
                ])->count();

            $lastMonthData[] = User::where('role', 'Candidate')
                ->whereBetween('created_at', [
                    $lastMonthWeeks[$i],
                    $lastMonthWeeks[$i + 1]
                ])->count();
        }
        $statistics = [
            'total_models' => User::where('role', 'Candidate')->count(),
            'total_members' => User::where('role', '!=', 'Candidate')->count(),
            'active_members' => User::where('role', '!=', 'Candidate')->where('status', 'active')->count(),
            'inactive_members' => User::where('role', '!=', 'Candidate')->where('status', 'inactive')->count(),
            'verified_models' => User::where('role', 'Candidate')
                ->whereHas('mannequinCandidate', function ($q) {
                    $q->where('verified', 'true');
                })->count(),
            'unverified_models' => User::where('role', 'Candidate')
                ->whereHas('mannequinCandidate', function ($q) {
                    $q->where('verified', '!=', 'true');
                })->count(),
            'new_models_this_month' => User::where('role', 'Candidate')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'models_last_month' => User::where('role', 'Candidate')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->count(),
            'this_month_weeks' => $thisMonthData,
            'last_month_weeks' => $lastMonthData,
        ];
        return view('mainviews.models', compact('models'));
    }

    public function getData()
    {
        $statistics = [
            'total_members' => User::where('role', '!=', 'Candidate')->count(),
            'active_members' => User::where('role', '!=', 'Candidate')->where('status', 'active')->count(),
            'inactive_members' => User::where('role', '!=', 'Candidate')->where('status', 'inactive')->count(),
            'total_models' => User::where('role', 'Candidate')->count(),
            'verified_models' => User::where('role', 'Candidate')->whereHas('mannequinCandidate', function ($q) {
                $q->where('verified', 'approved');
            })->count(),
            'unverified_models' => User::where('role', 'Candidate')->whereHas('mannequinCandidate', function ($q) {
                $q->where('verified', 'pending');
            })->count(),
            'new_models_this_month' => User::where('role', 'Candidate')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'models_last_month' => User::where('role', 'Candidate')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->count(),
            'this_month_weeks' => [],
            'last_month_weeks' => []
        ];

        return view('mainviews.tableau-de-bord', compact('statistics'));
    }

  public function store(Request $request)
{
    $validatedData = $request->validate([
        'username'            => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-]+$/'],
        'email'               => ['required', 'string', 'unique:users,email', 'max:255', 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/'],
        'password'            => ['required', 'string', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/'],
        'profile'             => 'nullable|image|mimes:jpeg,png,jpg,webp,tiff,raw,dng|max:1536',
        'tel'                 => ['required', 'string', 'regex:/^[0-9+]+$/', 'max:15'],
        'identity_document'   => 'required|mimes:jpeg,png,jpg,webp,tiff,raw,dng,pdf|max:1536',
        'images.*'            => 'nullable|image|mimes:jpeg,png,jpg,webp,tiff,raw,dng|max:1536',
        'gender_identity'     => ['required', 'in:Femme,Homme'],
        'langues_parlees'     => ['nullable', 'string', 'max:255'],
        'couleur_cheveux'     => ['nullable', 'string', 'max:255'],
        'couleur_yeux'        => ['nullable', 'string', 'max:255'],
        'sport_pratique'      => ['nullable', 'string', 'max:255'],
        'piercings'           => ['nullable', 'string', 'max:255'],
        'tatouages'           => ['nullable', 'string', 'max:255'],
        'instagram_link'      => 'nullable|url|max:255',
        'model_type'          => 'required|in:Model,Mannequin',
        'disponibilite_debut' => 'required|date',
        'disponibilite_fin'   => 'required|date|after_or_equal:disponibilite_debut',
        'finition_peau' => ['nullable', 'in:Mate,Lumineuse,Sèche,Grasse'],
        'sous_ton'      => ['nullable', 'in:Froid,Chaud,Neutre'],
        'niveau'        => ['nullable', 'string', 'max:20'],
        'emotions'      => ['nullable', 'in:Autorité,Sensualité,Glamour,Anticode,Opulence'],
        'categorie'     => ['nullable', 'in:Beauté,Commercial,Défilé,Photo shoot puissant'],
    ]);

    // ====================== CAS BOOKEEUSE ======================
    if (auth()->check() && auth()->user()->role === 'bookeuse') {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->withErrors(['general' => 'Admin introuvable']);
        }

        $profilePath = $request->hasFile('profile')
            ? $request->file('profile')->store("temp/profiles", 'public_direct')
            : null;

        $identityPath = $request->hasFile('identity_document')
            ? $request->file('identity_document')->store("temp/identities", 'public_direct')
            : null;

        $code = rand(100000, 999999);

        ActionCode::create([
            'code'       => $code,
            'action'     => 'create_candidate',
            'data'       => array_merge(
                collect($validatedData)->except(['profile', 'identity_document', 'images'])->toArray(),
                [
                    'profile'           => $profilePath,
                    'identity_document' => $identityPath,
                ]
            ),
            'user_id'    => auth()->id(),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::send('emails.verification-code', [
            'verificationCode' => $code,
            'action'           => 'la création d\'un candidat'
        ], function ($message) use ($admin) {
            $message->to($admin->email)->subject('Code de validation - Bookeuse');
        });

        return redirect()->route('models.verification.show')
            ->with('success', 'Code de validation envoyé à l\'admin.');
    }

    // ====================== CAS NORMAL (Admin ou autre) ======================
    try {
        $user = User::create([
            'name'     => $validatedData['username'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role'     => 'Candidate',
            'slug'     => null,
        ]);

        $user->slug = $user->generateUniqueSlug();
        $user->save();

        $profilePath = $request->hasFile('profile')
            ? $request->file('profile')->store("models/{$user->id}/profile", 'public_direct')
            : null;

        $identityDocumentPath = $request->file('identity_document')
            ->store("models/{$user->id}/identity", 'public_direct');

        $candidate = MannequinCandidate::create([
            'user_id'              => $user->id,
            'profile'              => $profilePath,
            'tel'                  => $validatedData['tel'],
            'identity_document'    => $identityDocumentPath,
            'status_model'         => 'pending',
            'verified'             => 'pending',
            'gender_identity'      => $validatedData['gender_identity'],
            'langues_parlees'      => $validatedData['langues_parlees'] ?? null,
            'couleur_cheveux'      => $validatedData['couleur_cheveux'] ?? null,
            'couleur_yeux'         => $validatedData['couleur_yeux'] ?? null,
            'sport_pratique'       => $validatedData['sport_pratique'] ?? null,
            'piercings'            => $validatedData['piercings'] ?? null,
            'tatouages'            => $validatedData['tatouages'] ?? null,
            'instagram_link'       => $validatedData['instagram_link'] ?? null,
            'model_type'           => $validatedData['model_type'],
            'disponibilite_debut'  => $validatedData['disponibilite_debut'],
            'disponibilite_fin'    => $validatedData['disponibilite_fin'],
            'finition_peau' => $validatedData['finition_peau'] ?? null,
            'sous_ton'      => $validatedData['sous_ton'] ?? null,
            'niveau'        => $validatedData['niveau'] ?? null,
            'emotions'      => $validatedData['emotions'] ?? null,
            'categorie'     => $validatedData['categorie'] ?? null,
        ]);

        // Sauvegarde des images supplémentaires
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store("models/{$user->id}/additional_images", 'public_direct');
                MannequinImage::create([
                    'user_id'      => $user->id,
                    'candidate_id' => $candidate->id,
                    'image_url'    => $imagePath,
                ]);
            }
        }

     
        return redirect()->route('models.create')  // ou le nom de ta route pour add-model.blade.php
            ->with('success', 'Le mannequin a été ajouté avec succès !');

    } catch (\Exception $e) {
        Log::error('Erreur création candidat : ' . $e->getMessage());
        return redirect()->back()
            ->withErrors(['general' => 'Erreur lors de la création : ' . $e->getMessage()])
            ->withInput();
    }
}

    public function profile($id)
    {
        $model = User::with(['mannequinCandidate.measurements', 'mannequinCandidate.images'])->findOrFail($id);
        $images = MannequinImage::where('user_id', $id)->get();

        return view('mainviews.model-profile', compact('model', 'images'));
    }


   public function destroy($id)
{
    // Bloquer bookeuse
    if (auth()->user()->role === 'bookeuse') {
        return redirect()->back()->with(['error' => 'Vous n\'avez pas la permission de supprimer ce mannequin.']);
    }

    try {
        $candidate = MannequinCandidate::with(['comments.replays', 'images'])->find($id);

        if (!$candidate) {
            return redirect()->back()->withErrors(['error' => 'Mannequin non trouvé.']);
        }

        DB::beginTransaction();

        if ($candidate->wordpress_post_id) {
            $wpResponse = $this->deleteMannequinFromWordpress($candidate->wordpress_post_id);
            if (isset($wpResponse['error'])) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'Erreur lors de la suppression WordPress: ' . $wpResponse['error']]);
            }
        }

        foreach ($candidate->comments as $comment) {
            $comment->replays()->delete();
        }

        $candidate->comments()->delete();
        $candidate->images()->delete();
        $candidate->delete();
        $candidate->user()->delete();
        $candidate->measurements()->delete();
        $candidate->ratings()->delete();

        DB::commit();

        return redirect()->back()->with(['success']);
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => 'Erreur lors de la suppression du mannequin: ' . $e->getMessage()]);
    }
    }


    public function edit($id)
    {
        $currentUser = Auth::user();
        $model = User::with(['mannequinCandidate.comments.user:id,name,role', 'mannequinCandidate.measurements', 'mannequinCandidate.ratings.judge', 'mannequinCandidate.contracts'])->find($id);

        if (!$model) {
            return redirect()->back()->withErrors(['error' => 'Mannequin non trouvé.']);
        }

        $candidateId = $model->mannequinCandidate->id;
        $images = MannequinImage::where('candidate_id', $candidateId)->orderBy('position')->get();

        $commentsQuery = Comment::query()->where('candidate_id', $candidateId)->with([
            'user',
            'replays' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }
        ]);

        if (!Gate::allows('is-admin')) {
            $commentsQuery->where('user_id', $currentUser->id);
        }

        $comments = $commentsQuery->with('user')->latest()->get();

        $hasRating = false;
        if ($currentUser->role === 'jury') {
            $hasRating = $model->mannequinCandidate->ratings()
                ->where('judge_id', $currentUser->id)
                ->exists();
        }

        return view('mainviews.edit-model', compact('model', 'images', 'comments'));
    }

    public function view($slugOrId)
    {
        $model = User::with(['mannequinCandidate.measurements'])
            ->where('slug', $slugOrId)
            ->orWhere('id', $slugOrId)
            ->firstOrFail();

        $candidateId = $model->mannequinCandidate->id;
        $images = MannequinImage::where('candidate_id', $candidateId)->get();

         $profilePhoto = $model->mannequinCandidate->profile
        ? asset('storage/' . $model->mannequinCandidate->profile)
        : ($images->isNotEmpty()
            ? asset('storage/' . $images->sortBy('position')->first()->image_url)
            : null);

        return view('mainviews.model-single', compact('model', 'images', 'profilePhoto'));
    }

    public function verify_model($id, $status)
    {
        try {
            $model = User::with('mannequinCandidate')->find($id);

            if (!$model) {
                return redirect()->route('dashboard')
                    ->withErrors(['error' => 'Mannequin non trouvé.']);
            }

            $model->mannequinCandidate->status_model = $status;
            $model->mannequinCandidate->save();

            Mail::send('emails.status-update', [
                'name' => $model->name,
                'status' => $status,
            ], function ($message) use ($model) {
                $message->to($model->email)
                    ->subject('Mise à jour du statut de votre candidature');
            });

            return redirect()->route('dashboard')
                ->with('success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la mise à jour du statut: ' . $e->getMessage()]);
        }
    }

    public function searchModels(Request $request)
    {
        $query = User::with('mannequinCandidate')->where('role', 'Candidate')->orderBy('created_at', 'desc');
        $currentUser = Auth::user();

        if ($currentUser->role === 'accueillant') {
            $query = $query->whereHas('mannequinCandidate', function ($q) {
                $q->where('status_model', 'pending')->where('verified', 'pending');
            });
        } else if ($currentUser->role === 'styliste') {
            $query->whereHas('mannequinCandidate', function ($q) {
                $q->where('status_model', 'approved')
                    ->where('verified', 'pending')
                    ->whereDoesntHave('measurements');
            });
        } else if ($currentUser->role === 'jury') {
            $query->whereHas('mannequinCandidate', function ($q) {
                $q->where('status_model', 'approved')
                    ->where('verified', 'pending')
                    ->whereHas('measurements');
            });
        } else if (!$currentUser->isAdmin()) {
        $query = $query->whereHas('mannequinCandidate', function ($q) {
        $q->where('status_model', 'approved')->where('verified', 'pending');
    });
    }

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('model_type')) {
            $query->whereHas('mannequinCandidate', function ($q) use ($request) {
                $q->whereIn('model_type', $request->model_type);
            });
        }

        if ($currentUser->role === 'admin' && $request->filled('status_model')) {
            $query->whereHas('mannequinCandidate', function ($q) use ($request) {
                $q->whereIn('status_model', $request->status_model);
            });
        }

        if ($currentUser->role === 'admin' && $request->filled('verified')) {
            $query->whereHas('mannequinCandidate', function ($q) use ($request) {
                $q->whereIn('verified', $request->verified);
            });
        }

        if ($request->filled('gender_identity')) {
            $query->whereHas('mannequinCandidate', function ($q) use ($request) {
                $q->whereIn('gender_identity', $request->gender_identity);
            });
        }

        $minValue = (float) $request->input('min', 0);
        $maxValue = (float) $request->input('max', 0);
        $exactNote = (float) $request->input('exact_note', 0);

        if ($exactNote > 0 && $minValue === 0 && $maxValue === 0) {
            $query->whereHas('mannequinCandidate', function ($q) use ($exactNote) {
                $q->whereHas('ratings', function ($subQuery) use ($exactNote) {
                    $subQuery->where('rating', '=', $exactNote);
                })->withCount('ratings')->having('ratings_count', '=', 1);
            });
        } else if ($minValue > 0 || $maxValue > 0) {
            $query->whereHas('mannequinCandidate.ratings', function ($q) use ($minValue, $maxValue) {
                if ($minValue > 0) {
                    $q->where('rating', '>=', $minValue);
                }
                if ($maxValue > 0) {
                    $q->where('rating', '<=', $maxValue);
                }
            });
        }

        //Filtre par disponibilité
        if ($request->filled('disponibilite_debut') && $request->filled('disponibilite_fin')) {
            $query->whereHas('mannequinCandidate', function ($q) use ($request) {
                $q->whereDate('disponibilite_debut', '<=', $request->disponibilite_fin)
                ->whereDate('disponibilite_fin', '>=', $request->disponibilite_debut);
            });
        }

        $models = $query->paginate(50);

        $thisMonth = now();
        $lastMonth = now()->subMonth();

        $thisMonthWeeks = collect([
            $thisMonth->copy()->startOfMonth()->startOfWeek(),
            $thisMonth->copy()->startOfMonth()->addWeek()->startOfWeek(),
            $thisMonth->copy()->startOfMonth()->addWeeks(2)->startOfWeek(),
            $thisMonth->copy()->startOfMonth()->addWeeks(3)->startOfWeek(),
            $thisMonth->copy()->endOfMonth()
        ]);

        $lastMonthWeeks = collect([
            $lastMonth->copy()->startOfMonth()->startOfWeek(),
            $lastMonth->copy()->startOfMonth()->addWeek()->startOfWeek(),
            $lastMonth->copy()->startOfMonth()->addWeeks(2)->startOfWeek(),
            $lastMonth->copy()->startOfMonth()->addWeeks(3)->startOfWeek(),
            $lastMonth->copy()->endOfMonth()
        ]);

        $thisMonthData = [];
        $lastMonthData = [];

        for ($i = 0; $i < 4; $i++) {
            $thisMonthData[] = User::where('role', 'Candidate')
                ->whereBetween('created_at', [
                    $thisMonthWeeks[$i],
                    $thisMonthWeeks[$i + 1]
                ])->count();

            $lastMonthData[] = User::where('role', 'Candidate')
                ->whereBetween('created_at', [
                    $lastMonthWeeks[$i],
                    $lastMonthWeeks[$i + 1]
                ])->count();
        }
        $statistics = [
            'total_models' => User::where('role', 'Candidate')->count(),
            'total_members' => User::where('role', '!=', 'Candidate')->count(),
            'active_members' => User::where('role', '!=', 'Candidate')->where('status', 'active')->count(),
            'inactive_members' => User::where('role', '!=', 'Candidate')->where('status', 'inactive')->count(),
            'verified_models' => User::where('role', 'Candidate')
                ->whereHas('mannequinCandidate', function ($q) {
                    $q->where('verified', 'true');
                })->count(),
            'unverified_models' => User::where('role', 'Candidate')
                ->whereHas('mannequinCandidate', function ($q) {
                    $q->where('verified', '!=', 'true');
                })->count(),
            'new_models_this_month' => User::where('role', 'Candidate')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'models_last_month' => User::where('role', 'Candidate')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->count(),
            'this_month_weeks' => $thisMonthData,
            'last_month_weeks' => $lastMonthData,
        ];

        return view('mainviews.models', compact('models', 'statistics'));
    }

    public function add_pictures_view($model_id)
    {
        return view('mainviews.add-model-photos', compact('model_id'));
    }

    public function store_pictures(Request $request)
    {
        $validatedData = $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp,tiff,raw,dng|max:1536',
            'model_id' => 'required',
            'comment' => 'nullable|string'
        ]);

        try {
            $candidate = MannequinCandidate::findOrFail($validatedData['model_id']);

            foreach ($request->file('images') as $image) {
                $imagePath = $image->store("models/{$candidate->user_id}/additional_images", 'public_direct');

                MannequinImage::create([
                    'user_id' => Auth::id(),
                    'candidate_id' => $candidate->id,
                    'image_url' => $imagePath,
                ]);
            }

            if ($request->filled('comment')) {
                Comment::create([
                    'user_id' => Auth::id(),
                    'candidate_id' => $candidate->id,
                    'comment_content' => $request->comment
                ]);
            }

            return redirect()->back()->with('success', 'Les images ont été ajoutées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'ajout des images: ' . $e->getMessage()]);
        }
    }


    public function updateVerified(Request $request, $id)
    {
        if (!Gate::allows('is-admin')) {
            return redirect()->back()->withErrors(['error' => 'Vous n\'avez pas l\'autorisation.']);
        }

        try {
            $validated = $request->validate([
                'verified' => ['required', 'string', 'in:true,false,pending']
            ]);

            $candidate = MannequinCandidate::findOrFail($id);
            $originalVerificationStatus = $candidate->verified;
            $candidate->verified = $validated['verified'];
            $candidate->save();

            $wordpressMessage = '';
            $hasWordpressError = false;

            if ($candidate->verified === 'true' && $originalVerificationStatus !== 'true' && $candidate->user) {
                $name = $candidate->user->name;
                $firstImage = $candidate->images()->orderBy('position')->first();
                $imageUrl = $firstImage ? 'https://app.shawnagency.fr/storage/' . $firstImage->image_url : 'https://app.shawnagency.fr/storage/' . $candidate->profile;
                $linkUrl = route('model.view', $candidate->user->slug);
                $modelType = $candidate->model_type;
                $wpResponse = $this->sendMannequinToWordpress($name, $imageUrl, $linkUrl, $candidate->id, $modelType);
                if (isset($wpResponse['message'])) {
                    $wordpressMessage = ' ' . $wpResponse['message'];
                } else if (isset($wpResponse['error'])) {
                    $wordpressMessage = ' WordPress Error: ' . $wpResponse['error'];
                    $hasWordpressError = true;
                }
            } elseif ($candidate->verified !== 'true' && $originalVerificationStatus === 'true' && $candidate->wordpress_post_id) {
                $wpResponse = $this->deleteMannequinFromWordpress($candidate->wordpress_post_id);
                if (isset($wpResponse['message'])) {
                    $wordpressMessage = ' ' . $wpResponse['message'];
                    $candidate->wordpress_post_id = null;
                    $candidate->save();
                } else if (isset($wpResponse['error'])) {
                    $wordpressMessage = ' WordPress Error: ' . $wpResponse['error'];
                    $hasWordpressError = true;
                }
            }

            Mail::send('emails.profile-update', [
                'name' => $candidate->user->name,
                'status' => $candidate->verified,
            ], function ($message) use ($candidate) {
                $message->to($candidate->user->email)
                    ->subject('Mise à jour du statut de votre candidature');
            });

            $flashMessage = 'Le statut de vérification a été mis à jour.';
            if ($hasWordpressError) {
                return redirect()->back()->withErrors(['error' => $flashMessage . $wordpressMessage]);
            } else {
                return redirect()->back()->with('success', $flashMessage);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }

    public function sendMannequinToWordpress($name, $imageUrl, $linkUrl, $candidateId, $modelType)
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false,
        ]);
        $wordpressApiUrl = 'https://shawnagency.fr/wp-json/my-api/v1/mannequins';

        try {
            $response = $client->post($wordpressApiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => env('WORDPRESS_API_KEY'),
                ],
                'json' => [
                    'name' => $name,
                    'image_url' => $imageUrl,
                    'link_url' => $linkUrl,
                    'model_type' => $modelType
                ],
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            if ($response->getStatusCode() === 201 && isset($data['id'])) {
                MannequinCandidate::where('id', $candidateId)->update(['wordpress_post_id' => $data['id']]);
            }
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Error communicating with WordPress: ' . $e->getMessage()];
        }
    }

    public function deleteMannequinFromWordpress($wordpressPostId)
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false,
        ]);
        $wordpressApiUrl = "https://shawnagency.fr/wp-json/my-api/v1/mannequins/{$wordpressPostId}";

        try {
            $response = $client->delete($wordpressApiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => env('WORDPRESS_API_KEY'),
                ],
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Error communicating with WordPress for deletion: ' . $e->getMessage()];
        }
    }

    public function removeImages(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = explode(',', $request->input('ids'));

        DB::beginTransaction();

        try {
            $images = MannequinImage::whereIn('id', $ids)->get();

            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
            }

            MannequinImage::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Les images sélectionnées ont été supprimées avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Erreur lors de la suppression des images: ' . $e->getMessage()]);
        }
    }

    public function downloadPdf(Request $request, $id)
    {
        try {
            $model = User::with(['mannequinCandidate', 'mannequinCandidate.measurements', 'mannequinCandidate.images'])->findOrFail($id);
            return response()->json($model);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

   public function submitContactForm(Request $request, $modelName)
{
    $model = User::where('name', $modelName)->firstOrFail();
    $mannequinCandidate = MannequinCandidate::where('user_id', $model->id)->firstOrFail();

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'tel' => 'required|string|max:20',
        'message' => 'required|string',
    ]);

    $demande = Demande::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'tel' => $validatedData['tel'],
        'message' => $validatedData['message'],
        'mannequin_candidate_id' => $mannequinCandidate->id,
        'status' => 0
    ]);

    if ($request->ajax()) {
        $count = Demande::where('mannequin_candidate_id', $mannequinCandidate->id)->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    $demandes = Demande::where('mannequin_candidate_id', $mannequinCandidate->id)->latest()->get();

    return redirect()->route('model.view', $model->name)
                     ->with('success')
                     ->with('demandes', $demandes);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $candidate = $user->mannequinCandidate;

        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-]+$/'],
            'email' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/'],
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp,tiff,raw,dng|max:1536',
            'tel' => ['required', 'string', 'regex:/^[0-9+]+$/', 'max:15'],
            'identity_document' => 'nullable|mimes:jpeg,png,jpg,webp,tiff,raw,dng,pdf|max:1536',
            'gender_identity' => ['required', 'in:Femme,Homme'],
            'status_model' => ['required', 'in:pending,approved,rejected'],
            'langues_parlees' => ['nullable', 'string', 'max:255'],
            'couleur_cheveux' => ['nullable', 'string', 'max:255'],
            'couleur_yeux' => ['nullable', 'string', 'max:255'],
            'sport_pratique' => ['nullable', 'string', 'max:255'],
            'piercings'      => ['nullable', 'string', 'max:255'],
            'tatouages'      => ['nullable', 'string', 'max:255'],
            'instagram_link' => 'nullable|url|max:255',
            'model_type' => 'required|in:Model,Mannequin',
            'disponibilite_debut' => 'required|date',
            'disponibilite_fin' => 'required|date|after_or_equal:disponibilite_debut',
            'finition_peau' => ['nullable', 'in:Mate,Lumineuse,Sèche,Grasse'],
            'sous_ton'      => ['nullable', 'in:Froid,Chaud,Neutre'],
            'niveau'        => ['nullable', 'string', 'max:20'],
            'emotions'      => ['nullable', 'in:Autorité,Sensualité,Glamour,Anticode,Opulence'],
            'categorie'     => ['nullable', 'in:Beauté,Commercial,Défilé,Photo shoot puissant'],
        ]);

        if (auth()->user()->role === 'bookeuse') {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->withErrors(['general' => 'Admin introuvable']);
        }

        // Stocker les fichiers temporairement
        $profilePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePath = $request->file('profile_picture')->store("temp/profiles", 'public_direct');
        }

        $identityPath = null;
        if ($request->hasFile('identity_document')) {
            $identityPath = $request->file('identity_document')->store("temp/identities", 'public_direct');
        }

        $code = rand(100000, 999999);

        ActionCode::create([
            'code'   => $code,
            'action' => 'update_candidate',
            'data'   => array_merge(
                collect($validatedData)->except(['profile_picture', 'identity_document'])->toArray(),
                [
                    'candidate_user_id' => $id,
                    'profile_picture'   => $profilePath,
                    'identity_document' => $identityPath,
                ]
            ),
            'user_id'    => auth()->id(),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::send('emails.verification-code', [
            'verificationCode' => $code,
            'action'           => 'la mise à jour d\'un candidat'
        ], function ($message) use ($admin) {
            $message->to($admin->email)->subject('Code de validation - Bookeuse (Mise à jour)');
        });

        return redirect()->route('models.verification.show')
            ->with('success', 'Code envoyé à l\'admin pour validation.');
    }

        try {
            $user->name = $validatedData['username'];
            $user->email = $validatedData['email'];
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }
            $user->slug = $user->generateUniqueSlug();
            $user->save();

            if ($request->hasFile('profile_picture')) {
                $profilePath = $request->file('profile_picture')->store("models/{$user->id}/profile", 'public_direct');
                $candidate->profile = $profilePath;
            }

            if ($request->hasFile('identity_document')) {
                $identityDocumentPath = $request->file('identity_document')->store("models/{$user->id}/identity", 'public_direct');
                $candidate->identity_document = $identityDocumentPath;
            }

            $candidate->tel = $validatedData['tel'];
            $candidate->gender_identity = $validatedData['gender_identity'];
            $candidate->langues_parlees = $validatedData['langues_parlees'];
            $candidate->couleur_cheveux = $validatedData['couleur_cheveux'];
            $candidate->couleur_yeux = $validatedData['couleur_yeux'];
            $candidate->sport_pratique = $validatedData['sport_pratique'] ?? false;
            $candidate->piercings = $validatedData['piercings'] ?? false;
            $candidate->tatouages = $validatedData['tatouages'] ?? false;
            $candidate->instagram_link = $validatedData['instagram_link'];
            $candidate->model_type = $validatedData['model_type'];
            $candidate->disponibilite_debut= $validatedData['disponibilite_debut'];
            $candidate->disponibilite_fin = $validatedData['disponibilite_fin'];
            $candidate->finition_peau = $validatedData['finition_peau'] ?? null;
            $candidate->sous_ton      = $validatedData['sous_ton'] ?? null;
            $candidate->niveau        = $validatedData['niveau'] ?? null;
            $candidate->emotions      = $validatedData['emotions'] ?? null;
            $candidate->categorie     = $validatedData['categorie'] ?? null;

            $oldStatus = $candidate->status_model;
            $newStatus = $validatedData['status_model'];

            $candidate->status_model = $newStatus;
            $candidate->save();

            if ($oldStatus !== $newStatus) {
                Mail::send('emails.status-update', [
                    'name' => $user->name,
                    'status' => $newStatus,
                ], function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Mise à jour du statut de votre candidature');
                });
            }
            $wordpressMessage = '';
            $hasWordpressError = false;
            if ($candidate->verified === 'true' && $candidate->wordpress_post_id) {
                $name = $candidate->user->name;
                $firstImage = $candidate->images()->orderBy('position')->first();
                $imageUrl = $firstImage ? 'https://app.shawnagency.fr/storage/' . $firstImage->image_url : 'https://app.shawnagency.fr/storage/' . $candidate->profile;
                $linkUrl = route('model.view', $candidate->user->slug);
                $modelType = $candidate->model_type;
                $wpResponse = $this->updateMannequinInWordpress($candidate->wordpress_post_id, $name, $imageUrl, $linkUrl, $modelType);
                if (isset($wpResponse['message'])) {
                    $wordpressMessage = ' ' . $wpResponse['message'];
                } else if (isset($wpResponse['error'])) {
                    $wordpressMessage = ' WordPress Error: ' . $wpResponse['error'];
                    $hasWordpressError = true;
                }
            }


            $flashMessage = 'Le profil du mannequin a été mis à jour avec succès.';
            if ($hasWordpressError) {
                return redirect()->back()->withErrors(['general' => $flashMessage . $wordpressMessage]);
            } else {
                return redirect()->back()->with('success', $flashMessage);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }

    public function updateImageOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:mannequin_images,id',
            'order.*.position' => 'required|integer|min:0'
        ]);

        try {
            $firstImage = null;
            $candidate = null;
            DB::transaction(function () use ($request, &$firstImage, &$candidate) {
                foreach ($request->order as $item) {
                    $image = MannequinImage::find($item['id']);
                    if ($image) {
                        $candidate = $image->candidate;
                    }
                    MannequinImage::where('id', $item['id'])
                        ->update(['position' => $item['position']]);
                }
                $firstImage = $this->getFirstImage($candidate->id);
            });
            $wordpressMessage = '';
            $hasWordpressError = false;

            if ($candidate && $candidate->verified === 'true' && $candidate->wordpress_post_id) {
                $name = $candidate->user->name;
                $imageUrl = $firstImage ? 'https://app.shawnagency.fr/storage/' . $firstImage->image_url : 'https://app.shawnagency.fr/storage/' . $candidate->profile;
                $linkUrl = route('model.view', $candidate->user->slug);
                $modelType = $candidate->model_type;

                $wpResponse = $this->updateMannequinInWordpress($candidate->wordpress_post_id, $name, $imageUrl, $linkUrl, $modelType);
                if (isset($wpResponse['message'])) {
                    $wordpressMessage = ' ' . $wpResponse['message'];
                } else if (isset($wpResponse['error'])) {
                    $wordpressMessage = ' WordPress Error: ' . $wpResponse['error'];
                    $hasWordpressError = true;
                }
            }
            if ($hasWordpressError) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating image order' . $wordpressMessage
                ], 500);
            } else {
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating image order' . $e->getMessage()
            ], 500);
        }
    }

    private function getFirstImage($candidateId)
    {
        $firstImage = MannequinImage::where('candidate_id', $candidateId)
            ->orderBy('position')
            ->first();
        return $firstImage;
    }

    public function updateMannequinInWordpress($wordpressPostId, $name, $imageUrl, $linkUrl, $modelType)
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false,
        ]);
        $wordpressApiUrl = "https://shawnagency.fr/wp-json/my-api/v1/mannequins/{$wordpressPostId}";

        try {
            $response = $client->put($wordpressApiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => env('WORDPRESS_API_KEY'),
                ],
                'json' => [
                    'name' => $name,
                    'image_url' => $imageUrl,
                    'link_url' => $linkUrl,
                    'model_type' => $modelType
                ],
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Error communicating with WordPress for updating: ' . $e->getMessage()];
        }
    }



    public function verifyCode(Request $request)
{
    $request->validate([
        'verification_code' => 'required|digits:6',
    ]);

    $record = ActionCode::where('code', $request->verification_code)
        ->whereIn('action', ['create_candidate', 'update_candidate','add_contract', 'add_measurements','update_measurements', 'create_member','update_member'])
        ->where('expires_at', '>=', now())
        ->latest()
        ->first();

    if (!$record) {
        return back()->withErrors(['verification_code' => 'Code invalide ou expiré']);
    }

    $data = $record->data;

    if (!$data) {
        return back()->withErrors(['general' => 'Données introuvables.']);
    }

    // ========== CAS : CREATE ==========
    if ($record->action === 'create_candidate') {

        $requiredKeys = ['email', 'username', 'password', 'gender_identity', 'model_type', 'disponibilite_debut', 'disponibilite_fin'];
        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                return back()->withErrors(['general' => "La donnée obligatoire '$key' est manquante."]);
            }
        }

        try {
            $user = User::create([
                'name'     => $data['username'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => 'Candidate',
                'slug'     => null,
            ]);
            $user->slug = $user->generateUniqueSlug();
            $user->save();

            // CORRECTION : clés cohérentes avec ce qui a été sauvegardé dans store()
            $profilePath   = $data['profile'] ?? null;
            $identityPath  = $data['identity_document'] ?? null;

            // Déplacer les fichiers temp vers dossier définitif
            if ($profilePath && Storage::disk('public')->exists($profilePath)) {
                $finalProfile = "models/{$user->id}/profile/" . basename($profilePath);
                Storage::disk('public')->move($profilePath, $finalProfile);
                $profilePath = $finalProfile;
            }

            if ($identityPath && Storage::disk('public')->exists($identityPath)) {
                $finalIdentity = "models/{$user->id}/identity/" . basename($identityPath);
                Storage::disk('public')->move($identityPath, $finalIdentity);
                $identityPath = $finalIdentity;
            }

            MannequinCandidate::create([
                'user_id'             => $user->id,
                'profile'             => $profilePath,
                'tel'                 => $data['tel'] ?? null,
                'identity_document'   => $identityPath,
                'status_model'        => 'pending',
                'verified'            => 'pending',
                'gender_identity'     => $data['gender_identity'],
                'langues_parlees'     => $data['langues_parlees'] ?? null,
                'couleur_cheveux'     => $data['couleur_cheveux'] ?? null,
                'couleur_yeux'        => $data['couleur_yeux'] ?? null,
                'sport_pratique'      => $data['sport_pratique'] ?? false,
                'piercings'           => $data['piercings'] ?? false,
                'tatouages'           => $data['tatouages'] ?? false,
                'instagram_link'      => $data['instagram_link'] ?? null,
                'model_type'          => $data['model_type'],
                'disponibilite_debut' => $data['disponibilite_debut'],
                'disponibilite_fin'   => $data['disponibilite_fin'],
            ]);

            $record->delete();

            return redirect()->route('dashboard')->with('success', 'Candidat créé avec succès.');

        } catch (\Exception $e) {
            \Log::error('verifyCode create error', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Erreur lors de la création : ' . $e->getMessage()]);
        }
    }

    // ========== CAS : UPDATE ==========
    if ($record->action === 'update_candidate') {

        $requiredKeys = ['candidate_user_id', 'username', 'email', 'gender_identity', 'model_type', 'disponibilite_debut', 'disponibilite_fin'];
        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                return back()->withErrors(['general' => "La donnée obligatoire '$key' est manquante."]);
            }
        }

        try {
            $user      = User::findOrFail($data['candidate_user_id']);
            $candidate = $user->mannequinCandidate;

            $user->name  = $data['username'];
            $user->email = $data['email'];
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->slug = $user->generateUniqueSlug();
            $user->save();

            if (!empty($data['profile_picture']) && Storage::disk('public')->exists($data['profile_picture'])) {
                $finalPath = "models/{$user->id}/profile/" . basename($data['profile_picture']);
                Storage::disk('public')->move($data['profile_picture'], $finalPath);
                $candidate->profile = $finalPath;
            }

            if (!empty($data['identity_document']) && Storage::disk('public')->exists($data['identity_document'])) {
                $finalPath = "models/{$user->id}/identity/" . basename($data['identity_document']);
                Storage::disk('public')->move($data['identity_document'], $finalPath);
                $candidate->identity_document = $finalPath;
            }

            $candidate->tel                 = $data['tel'] ?? $candidate->tel;
            $candidate->gender_identity     = $data['gender_identity'];
            $candidate->langues_parlees     = $data['langues_parlees'] ?? null;
            $candidate->couleur_cheveux     = $data['couleur_cheveux'] ?? null;
            $candidate->couleur_yeux        = $data['couleur_yeux'] ?? null;
            $candidate->sport_pratique      = $data['sport_pratique'] ?? false;
            $candidate->piercings           = $data['piercings'] ?? false;
            $candidate->tatouages           = $data['tatouages'] ?? false;
            $candidate->instagram_link      = $data['instagram_link'] ?? null;
            $candidate->model_type          = $data['model_type'];
            $candidate->disponibilite_debut = $data['disponibilite_debut'];
            $candidate->disponibilite_fin   = $data['disponibilite_fin'];

            $oldStatus = $candidate->status_model;
            $newStatus = $data['status_model'];
            $candidate->status_model = $newStatus;
            $candidate->save();

            if ($oldStatus !== $newStatus) {
                Mail::send('emails.status-update', [
                    'name'   => $user->name,
                    'status' => $newStatus,
                ], function ($message) use ($user) {
                    $message->to($user->email)->subject('Mise à jour du statut de votre candidature');
                });
            }

            $record->delete();

            return redirect()->route('model.modify', $data['candidate_user_id'])
                 ->with('success', 'Candidat mis à jour avec succès.');


        } catch (\Exception $e) {
            \Log::error('verifyCode update error', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }


    // ========== CAS : ADD CONTRACT ==========
    if ($record->action === 'add_contract') {
    $data = $record->data;

    try {
        $candidate = MannequinCandidate::findOrFail($data['candidate_id']);

        // Déplacer le fichier du dossier temp vers le dossier définitif
        $finalPath = "models/{$candidate->user_id}/contracts/" . basename($data['contract_path']);

        if (Storage::disk('public')->exists($data['contract_path'])) {
            Storage::disk('public')->move($data['contract_path'], $finalPath);
        }

        Contract::create([
            'candidate_id' => $candidate->id,
            'user_id'      => $data['user_id'] ?? auth()->id(),
            'contract_url' => $finalPath,
            'status'       => 'pending'
        ]);

        $record->delete();

       return redirect()->route('model.modify', $candidate->user_id)
                 ->with('success', 'Le contrat a été ajouté avec succès.');

    } catch (\Exception $e) {
        \Log::error('verifyCode add_contract error', ['error' => $e->getMessage()]);
        return back()->withErrors(['general' => 'Erreur lors de l\'ajout du contrat : ' . $e->getMessage()]);
    }
}

// ========== CAS : ADD MEASUREMENTS ==========
    if ($record->action === 'add_measurements') {
    $data = $record->data;

    try {
        $candidate = MannequinCandidate::findOrFail($data['candidate_id']);

        $measurementData = collect($data)->except(['candidate_id', 'user_id', 'comment'])->toArray();
        $measurementData['candidate_id'] = $candidate->id;
        $measurementData['user_id']      = $data['user_id'] ?? auth()->id();

        MannequinMeasurement::create($measurementData);

        // Commentaire si présent
        if (!empty($data['comment'])) {
            Comment::create([
                'user_id'         => $data['user_id'] ?? auth()->id(),
                'candidate_id'    => $candidate->id,
                'comment_content' => $data['comment']
            ]);
        }

        $record->delete();

        return redirect()->route('model.modify', $data['model_user_id'] ?? $candidate->user_id)
                 ->with('success', 'Les mensurations ont été ajoutées avec succès.');

    } catch (\Exception $e) {
        \Log::error('verifyCode add_measurements error', ['error' => $e->getMessage()]);
        return back()->withErrors(['general' => 'Erreur lors de l\'ajout des mensurations : ' . $e->getMessage()]);
    }}


 // ========== CAS : UPDATE MEASUREMENTS ==========
    if ($record->action === 'update_measurements') {

    if (!isset($data['measurement_id'])) {
        return back()->withErrors(['general' => 'ID de mensuration manquant.']);
    }

    try {
        $measurement = MannequinMeasurement::findOrFail((int) $data['measurement_id']);
        $measurementData = collect($data)
            ->except(['measurement_id', 'user_id'])
            ->toArray();

        $measurement->update($measurementData);

        $record->delete();

        return redirect()->route('model.modify', $data['model_user_id'])
                 ->with('success', 'Mensurations mises à jour avec succès.');

    } catch (\Exception $e) {
        \Log::error('verifyCode update_measurements error', ['error' => $e->getMessage()]);
        return back()->withErrors(['general' => 'Erreur : ' . $e->getMessage()]);
    }
}



// ========== CAS : CREATE MEMBER ==========
    if ($record->action === 'create_member') {
    try {
        User::create([
            'name'     => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        $record->delete();

        return redirect()->route('dashboard.members')
                         ->with('success', 'Membre ajouté avec succès.');

    } catch (\Exception $e) {
        \Log::error('verifyCode create_member error', ['error' => $e->getMessage()]);
        return back()->withErrors(['general' => 'Erreur : ' . $e->getMessage()]);
    }
}

// ========== CAS : UPDATE MEMBER ==========
    if ($record->action === 'update_member') {
    try {
        $member       = User::findOrFail((int) $data['member_id']);
        $member->name  = $data['username'];
        $member->email = $data['email'];
        $member->role  = $data['role'];

        if (!empty($data['password'])) {
            $member->password = Hash::make($data['password']);
        }

        $member->save();

        $record->delete();

        return redirect()->route('dashboard.members', $data['member_id'])
                         ->with('success', 'Membre mis à jour avec succès.');

    } catch (\Exception $e) {
        \Log::error('verifyCode update_member error', ['error' => $e->getMessage()]);
        return back()->withErrors(['general' => 'Erreur : ' . $e->getMessage()]);
    }
}
    return back()->withErrors(['general' => 'Action inconnue.']);
    }
    public function showVerificationForm()
    {
    return view('auth.verify-code');
    }
}
