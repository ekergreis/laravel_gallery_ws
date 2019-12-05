<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function galerie() {
        return $this->belongsTo(Galerie::class);
    }
    public function comment() {
        return $this->hasMany(Comment::class);
    }
    public function like() {
        return $this->hasMany(Like::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getNbCommentAttribute() {
        //dd($this->comment->count());
        return $this->comment->count();
    }
    public function getNbLikeAttribute() {
        return $this->like->count();
    }
}
