<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name'];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function galerie()
    {
        return $this->belongsToMany(Galerie::class)->withTimestamps();
    }
}
