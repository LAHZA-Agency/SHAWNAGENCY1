<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'candidate_id', 'comment_content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function candidate()
    {
        return $this->belongsTo(MannequinCandidate::class, 'candidate_id');
    }
    public function adminReply()
    {
        return $this->hasMany(AdminReply::class);
    }
    public function replays()
    {
        return $this->hasMany(AdminReply::class, 'comment_id');
    }
}
