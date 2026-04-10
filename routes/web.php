<?php

use App\Http\Controllers\AdminReplyController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\MannequinMeasurementController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\DemandeController;
use App\Models\Demande;
use App\Http\Controllers\CalendrierController;
use App\Http\Controllers\ModelRatingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Authentication Routes
require __DIR__ . '/auth.php';

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/mannequin/inscription', function () {
        return view('mainviews.model-inscription');
    });


    Route::get('/mannequin/connexion', function () {
        return view('mainviews.model-login');
    });

    Route::post('/mannequin/nouvelleinscription', [ModelController::class, 'store'])->name('models.new-inscription');

    Route::get('/connection/verifier', [AuthenticatedSessionController::class, 'showVerificationForm'])->name('verification.show');
    Route::post('/connection/verifier-code', [AuthenticatedSessionController::class, 'verifyCode'])->name('verification.submit_code');
});


// Base authenticated routes (accessible by all authenticated users)
Route::middleware(['auth', 'checkStatus'])->group(function () {
    Route::get('/tableau-de-bord/les-mannequins', [ModelController::class, 'index'])->name('dashboard');
    Route::get('/mannequin/{id}', [ModelController::class, 'profile'])->name('model.profile');
    Route::post('/contact', [ModelController::class, 'submitContactForm'])->name('contact.submit');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Models Routes
    Route::get('/tableau-de-bord/les-mannequins/filter', [ModelController::class, 'searchModels'])->name('models.filter');
    Route::get('/tableau-de-bord/les-mannequins/search', [ModelController::class, 'searchModels'])->name('models.search');
    Route::get('/tableau-de-bord/mannequin/mise-a-jour/{id}', [ModelController::class, 'edit'])->name('model.modify');

    // Comments Routes
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Admin-only routes
Route::middleware(['auth', 'checkStatus', 'checkAdmin'])->group(function () {

    // Models Routes
    Route::delete('/mannequin/{id}', [ModelController::class, 'destroy'])->name('model.remove');
    Route::get('/tableau-de-bord/mannequin/ajouter-un-mannequin', function () {
        return view('mainviews.add-model');
    })->name('dashboard.add-model');
    Route::post('/mannequin/store', [ModelController::class, 'store'])->name('models.store');
    Route::post('/mannequin/update-verified/{id}', [ModelController::class, 'updateVerified'])->name('model.update.verified');
    Route::put('/mannequin/update/{id}', [App\Http\Controllers\ModelController::class, 'update'])->name('model.update');
    // Members Routes
    Route::get('/tableau-de-bord/les-membres', [MemberController::class, 'index'])->name('dashboard.members');
    Route::get('/tableau-de-bord/les-membres/filter', [MemberController::class, 'searchMembers'])->name('members.filter');
    Route::get('/tableau-de-bord/les-membres/search', [MemberController::class, 'searchMembers'])->name('members.search');
    Route::get('/tableau-de-bord/member/ajouter-un-membre', function () {
        return view('mainviews.add-member');
    })->name('dashboard.add-member');
    Route::get('/tableau-de-bord/member/mise-a-jour/{id}', [MemberController::class, 'edit'])->name('member.modifier');
    Route::put('/member/{id}', [MemberController::class, 'update'])->name('member.update');
    Route::delete('/member/{id}', [MemberController::class, 'destroy'])->name('member.destroy');
    Route::post('/member/store', [MemberController::class, 'store'])->name('member.store');
    Route::post('/member/update-status/{userId}/{status}', [MemberController::class, 'updateStatus']);

    // Contracts Routes
    Route::post('/contracts/{candidate}', [ContractController::class, 'store'])->name('contracts.store');
    Route::patch('/contracts/{contract}/status', [ContractController::class, 'updateStatus'])->name('contracts.status.update');

    // Comments replies Routes
    Route::post('/admin-reply', [AdminReplyController::class, 'store'])->name('admin-reply.store');
    Route::delete('/admin-reply/{id}', [AdminReplyController::class, 'destroy'])->name('admin-reply.destroy');

    // Rating Routes
    Route::delete('/tableau-de-bord/mannequin/model-rating/{id}', [ModelRatingController::class, 'destroy'])->name('model-rating.destroy');
    Route::delete('/tableau-de-bord/mannequin/model-contract/{id}', [ContractController::class, 'destroy'])->name('model-contract.destroy');

    // images Routes
    Route::delete('/tableau-de-bord/mannequin/remove-images', [ModelController::class, 'removeImages'])->name('remove-images');

    // Measures updates Route
    Route::put('/tableau-de-bord/mannequin/update-measurements/{id}', [MannequinMeasurementController::class, 'updateMeasurements'])->name('model.update.measurements');

    // Route::get('/tableau-de-bord', function () {
    //     return view('mainviews.tableau-de-bord');
    // })->name('tableau-de-bord');

    Route::get('/tableau-de-bord', [ModelController::class, 'getData'])->name('tableau-de-bord');

    // Chagne images position
    Route::post('/admin/model/update-image-order', [ModelController::class, 'updateImageOrder'])
    ->name('model.update.image.order');

    // Voir tous les demandes
    Route::get('/demandes', [DemandeController::class, 'view'])->name('demandes.view');
    Route::delete('/demandes/{id}/delete', [DemandeController::class, 'destroy'])->name('demandes.delete');
    Route::get('/api/demandes/count', function () {
    if (!Auth::check()) return response()->json(['count' => 0]);
    
    $user = Auth::user();
    
    if ($user->role === 'admin') {
        $count = Demande::where('seen_by_admin', 0)->count();
    } else {
        $count = Demande::where('status', 0)->count();
    }
    
    return response()->json(['count' => $count]);
});

    //Calandrier
    Route::get('/calendrier', [CalendrierController::class, 'View'])->name('calendar.view');
    Route::get('/calendrier/disponibilites', [CalendrierController::class, 'getModelAvailability']);
    Route::get('/calendrier/search', [CalendrierController::class, 'search'])
    ->name('calendrierDespo.search');


    Route::post('/verify-code', [ModelController::class, 'verifyCode'])->name('models.verifyCode');
    Route::get('/verification-code', [ModelController::class, 'showVerificationForm'])
    ->name('models.verification.show');
});

// Accueillant-only routes
Route::middleware(['auth', 'checkStatus', 'accueillant'])->group(function () {
    Route::get('/tableau-de-bord/mannequin/verifications/{id}/{status}', [ModelController::class, 'verify_model'])
        ->name('dashboard.model_verify');
});

// Photographe-only routes
Route::middleware(['auth', 'checkStatus', 'photographe'])->group(function () {
    Route::get('/tableau-de-bord/mannequin/ajouter-photos/{id}', [ModelController::class, 'add_pictures_view'])->name('model.add.photos');
});

// Mensurations-only routes
Route::middleware(['auth', 'checkStatus', 'mensuration'])->group(function () {
    Route::get('/tableau-de-bord/mannequin/ajouter-mensurations/{id}', [MannequinMeasurementController::class, 'create'])->name('model.create.measurements');
});

// Jury-admin-only routes
Route::middleware(['auth', 'checkStatus', 'juryOrAdmin'])->group(function () {
    Route::post('/tableau-de-bord/mannequin/model-rating', [ModelRatingController::class, 'store'])->name('model-rating.store');
});

// Photographe-admin-only routes
Route::middleware(['auth', 'checkStatus', 'photographeOrAdmin'])->group(function () {
    Route::post('/tableau-de-bord/mannequin/transferer-photos/', [ModelController::class, 'store_pictures'])->name('model.store.photos');
});

// Mensuration-admin-only routes
Route::middleware(['auth', 'checkStatus', 'mensurationOrAdmin'])->group(function () {
    Route::post('/tableau-de-bord/mannequin/store-mensurations/{id}', [MannequinMeasurementController::class, 'store'])->name('model.store.measurements');
});

// Fallback Route for Unauthorized Access
Route::fallback(function () {
    return redirect('/login');
});

Route::get('/forbidden', function () {
    return view('mainviews.forbidden');
})->name('mainviews.forbidden');


Route::get('/{slug}', [ModelController::class, 'view'])->name('model.view');
Route::get('/tableau-de-bord/mannequin/profile/{id}', [ModelController::class, 'view'])->name('model.view.legacy');

Route::post('/contact/{modelName}', [ModelController::class, 'submitContactForm'])->name('contact.submit');

Route::post('/model/{id}/download-pdf', [ModelController::class, 'downloadPdf'])->name('model.download.pdf');
