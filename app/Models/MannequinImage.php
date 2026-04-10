<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MannequinImage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'candidate_id', 'image_url','position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function candidate()
    {
        return $this->belongsTo(MannequinCandidate::class, 'candidate_id');
    }

    protected static function booted()
    {
        static::deleting(function ($image) {
            // Delete the file from storage
            if (Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }
        });
    }
}
