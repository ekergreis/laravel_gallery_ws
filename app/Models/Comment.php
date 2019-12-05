<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function image() {
        return $this->belongsTo(Image::class);
    }
    public function like() {
        return $this->hasMany(Like::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getNbLikeAttribute() {
        return $this->like->count();
    }
}
