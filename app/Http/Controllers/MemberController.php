<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Models\ActionCode;
class MemberController extends Controller
{
    // get All memer
    public function index()
    {
        $users = User::where('role', '!=', 'Candidate')->orderBy('created_at', 'desc')->paginate(50);
        return view('mainviews.members', compact('users'));
    }

    // Store new user data
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'bookeuse'])) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas l\'autorisation d\'ajouter un membre.');
        }

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-]+$/'],
            'email' => ['required', 'email','unique:users,email','max:255', 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/'],
            'password' => ['required', 'string', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/'],
            'role' => ['required', 'in:admin,bookeuse,accueillant,styliste,photographe,jury,psychologue,coach,dieteticien,osteopathe'],
        ]);
        if (auth()->user()->role === 'bookeuse') {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->withErrors(['general' => 'Admin introuvable']);
        }

        $code = rand(100000, 999999);

        ActionCode::create([
            'code'       => $code,
            'action'     => 'create_member',
            'data'       => [
                'username' => $request->username,
                'email'    => $request->email,
                'password' => $request->password, // mot de passe en clair temporairement
                'role'     => $request->role,
            ],
            'user_id'    => auth()->id(),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::send('emails.verification-code', [
            'verificationCode' => $code,
            'action'           => 'l\'ajout d\'un membre',
        ], function ($message) use ($admin) {
            $message->to($admin->email)
                    ->subject('Code de validation - Bookeuse (Ajout Membre)');
        });

        return redirect()->route('models.verification.show')
                         ->with('success', 'Code envoyé à l\'admin pour validation.');
    }

        try {

            User::create([
                'name' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return Redirect::back()->with(['success' => 'Le membre est ajouté avec succès.']);
        } 
        catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['general' => 'Erreur lors de la création de membre: ' . $e->getMessage()]);
        }
    }

    // Update status of members
    public function updateStatus($userId, $status)
    {
        if (!Gate::allows('is-admin')) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de mettre à jour le statut.'], status: 403);
        }

        $user = User::find($userId);
        if ($user) {
            $user->status = $status;
            $user->save();
            return response()->json(['success' => 'Le statut a été mis à jour avec succès']);
        }

        return response()->json(['error' => 'Membre introuvable'], 404);
    }

    // Filter/search fro member
    public function searchMembers(Request $request)
    {
        $query = User::query();

        $query->where('role', '!=', 'Candidate')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }
        if ($request->filled('role')) {
            $query->whereIn('role', $request->role);
        }
        $users = $query->paginate(50);
        $roleMapping = [
            'accueillant' => 'Accueillant',
            'admin' => 'Admin',
            'bookeuse'=> 'bookeuse',
            'coach' => 'Coach sportif',
            'dieteticien' => 'Diététicien',
            'jury' => 'Jury',
            'styliste' => 'Mensurations/styliste',
            'osteopathe' => 'Ostéopathe',
            'photographe' => 'Photographe',
            'psychologue' => 'Psychologue',
        ];
        return view('mainviews.members', compact('users', 'roleMapping'));
    }

    // delete member
    public function destroy($id)
    {

        if (!Gate::allows('is-admin')) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de mettre à jour le statut.'], status: 403);
        }

        $member = User::find($id);

        if ($member) {
            $member->delete();
            return response()->json(['success' => 'Membre supprimé avec succès.']);
        }

        return response()->json(['error' => 'Membre introuvable'], 404);
    }

    // redirect to update view
    public function edit($id)
    {
        $member = User::findOrFail($id);
        return view('mainviews.update-member', compact('member'));
    }

    // update member
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-]+$/'],
            'email' => ['required', 'email',Rule::unique('users', 'email')->ignore($id), 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/'],
            'password' => ['nullable', 'string', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/'],
            'role' => ['required', 'in:admin,bookeuse,accueillant,styliste,photographe,jury,psychologue,coach,dieteticien,osteopathe'],
        ]);

         if (auth()->user()->role === 'bookeuse') {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->withErrors(['general' => 'Admin introuvable']);
        }

        $code = rand(100000, 999999);

        ActionCode::create([
            'code'       => $code,
            'action'     => 'update_member',
            'data'       => [
                'member_id' => (int) $id,
                'username'  => $request->username,
                'email'     => $request->email,
                'password'  => $request->password, 
                'role'      => $request->role,
            ],
            'user_id'    => auth()->id(),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::send('emails.verification-code', [
            'verificationCode' => $code,
            'action'           => 'la mise à jour d\'un membre',
        ], function ($message) use ($admin) {
            $message->to($admin->email)
                    ->subject('Code de validation - Bookeuse (Mise à jour Membre)');
        });

        return redirect()->route('models.verification.show')
                         ->with('success', 'Code envoyé à l\'admin pour validation.');
    }
    

    try {
        $member       = User::findOrFail($id);
        $member->name  = $request->username;
        $member->email = $request->email;
        $member->role  = $request->role;

        if ($request->filled('password')) {
            $member->password = Hash::make($request->password);
        }

        $member->save();

        return redirect()->back()->with('success', 'Le membre est mis à jour avec succès.');
    } catch (\Exception $e) {
        return redirect()->back()
            ->withErrors(['general' => 'Erreur : ' . $e->getMessage()]);
    }

        try {

            $member = User::findOrFail($id);
            $member->name = $request->input('username');
            $member->email = $request->input('email');
            $member->role = $request->input('role');
            if ($request->filled('password')) {
                $member->password = Hash::make($request->password);
            }

            $member->save();

            
            return Redirect::back()->with(['success' => 'Le membre est ajouté avec succès.']);
        } 
        catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['general' => 'Erreur lors de la création de membre: ' . $e->getMessage()]);
        }
    }
}
