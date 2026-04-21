<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MannequinCandidate extends Model
{
    protected $table = 'mannequin_candidates';

    protected $fillable = [
        'user_id',
        'profile',
        'status_model',
        'verified',
        'tel',
        'identity_document',
        'gender_identity',
        'langues_parlees',
        'couleur_cheveux',
        'couleur_yeux',
        'sport_pratique',
        'piercings',
        'tatouages',
        'instagram_link',
        'model_type',
        'disponibilite_debut',
        'disponibilite_fin',
        'finition_peau',
        'sous_ton',
        'niveau',
        'emotions',
        'categorie',
    ];

    protected $casts = [
        'disponibilite_debut' => 'date',
        'disponibilite_fin' => 'date',
    ];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with MannequinImage
    public function images()
    {
        return $this->hasMany(MannequinImage::class, 'candidate_id');
    }

    // Relationship with Comments
    public function comments()
    {
        return $this->hasMany(Comment::class, 'candidate_id');
    }

    // Relationship with Measurements
    public function measurements()
    {
        return $this->hasMany(MannequinMeasurement::class, 'candidate_id');
    }

    // Boot method to handle cascading deletions
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($candidate) {
            // Delete related images
            foreach ($candidate->images as $image) {
                $image->delete();
            }

            // Delete related folder from storage
            $folderPath = "models/{$candidate->user_id}";
            if (Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->deleteDirectory($folderPath);
            }

            // Delete related measurements
            $candidate->measurements()->delete();

            // Delete related comments
            $candidate->comments()->delete();

            // Delete all contracts associated with the candidate
            foreach ($candidate->contracts as $contract) {
                Storage::disk('public')->delete($contract->contract_url);
                $contract->delete();
            }
        });
    }

    public function ratings()
    {
        return $this->hasMany(ModelRating::class, 'candidate_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'candidate_id');
    }
}
