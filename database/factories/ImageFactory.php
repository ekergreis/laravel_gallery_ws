<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;

use App\Classes\TraitementImages;
use App\Models\Image;
use App\Models\Galerie;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    $galerie=Galerie::all()->random();
    if(App::runningUnitTests()) {
        $filename=Str::random(config('gallery.img_token_lenght'));
    } else {
        $filename=$faker->image('public/storage/galeries_images/'.$galerie->path, 800, 600, null, false);
        $traitImg = new TraitementImages();
        $traitImg->creationMiniature('public/storage/galeries_images/'.$galerie->path, $filename);
    }

    return [
        'galerie_id' => $galerie->id,
        'filename' => $filename,
        'checksum' => '',
        'user_id' => User::all()->random()->id,
    ];
});
