<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MannequinMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'user_id',
        'head_circumference',
        'neck_base_circumference',
        'shoulder_length',
        'arm_length',
        'front_width',
        'chest_circumference',
        'waist_circumference',
        'small_hips_circumference',
        'hips_circumference',
        'thigh_circumference',
        'knee_circumference',
        'calf_circumference',
        'ankle_circumference',
        'upper_arm_circumference',
        'elbow',
        'forearm_circumference',
        'wrist_size',
        'wrist_to_elbow',
        'inseam_length',
        'knee_height',
        'side_height',
        'total_height',
        'pointure',
        'confection',
        'poids',
        'tour_de_hanches',
        'belt_circumference',
    ];

    public function candidate()
    {
        return $this->belongsTo(MannequinCandidate::class, 'candidate_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
