<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'candidate_id',
        'user_id',
        'contract_url',
        'status'
    ];

    public function candidate()
    {
        return $this->belongsTo(MannequinCandidate::class, 'candidate_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
