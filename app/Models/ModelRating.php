<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelRating extends Model
{
    protected $fillable = [
        'judge_id',
        'candidate_id',
        'rating',
        'comment'
    ];

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function candidate()
    {
        return $this->belongsTo(MannequinCandidate::class, 'candidate_id');
    }
}
