<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'slug',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function mannequinCandidate()
    {
        return $this->hasOne(MannequinCandidate::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function adminReply()
    {
        return $this->hasMany(AdminReply::class);
    }
    
    public function generateUniqueSlug()
    {
        $nameParts = explode(' ', $this->name);
        $slug = '';

        if (count($nameParts) >= 2) {
            $slug = Str::slug($nameParts[0]);

            if (count($nameParts) == 2) {
                $slug .= '-' . Str::lower(substr($nameParts[1], 0, 1));
            } else {

                for ($i = 1; $i < count($nameParts); $i++) {
                    $slug .= '-' . Str::lower(substr($nameParts[$i], 0, 1));
                }
            }
        } else {
            $slug = Str::slug($this->name);
        }

        $count = 1;

        while (User::where('slug', $slug)
            ->where('id', '!=', $this->id)
            ->exists()
        ) {
            $baseSlug = $slug;
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }


    public function isAdmin()
    {
    return in_array($this->role, ['admin', 'bookeuse']);
    }
}
