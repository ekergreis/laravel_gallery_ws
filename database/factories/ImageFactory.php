<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;

use App\Models\Image;
use App\Models\Galerie;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    $galerie=Galerie::all()->random();
    if(App::runningUnitTests()) {
        $filename=Str::random(32);
    } else {
        $filename=$faker->image('public/storage/galeries_images/'.$galerie->path,400,300, null, false);
    }

    return [
        'galerie_id' => $galerie->id,
        'filename' => $filename,
        'user_id' => User::all()->random()->id,
    ];
});
