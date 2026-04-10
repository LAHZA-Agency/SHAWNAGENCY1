<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = ['name', 'email', 'tel', 'message', 'mannequin_candidate_id','status', 'seen_by_admin'];

    public function mannequinCandidate()
    {
        return $this->belongsTo(MannequinCandidate::class);
    }
}
