<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galerie extends Model
{
    protected $fillable = ['name', 'description', 'date_start', 'date_end', 'path', 'user_id'];
    protected $dates = ['date_start', 'date_end'];

    public function group()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }
    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function getNbImageAttribute() {
        return $this->image->count();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
