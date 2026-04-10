<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminReply extends Model
{
    protected $fillable = ['comment_id', 'admin_id', 'reply_content'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
