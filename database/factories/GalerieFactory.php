<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

use App\Models\Galerie;
use App\Models\User;


$factory->define(Galerie::class, function (Faker $faker) {
    $dateDeb = $faker->dateTimeBetween('-3 years', 'now');
    $dateFin = $faker->dateTimeInInterval($dateDeb, '45 days');
    $directory = Str::random(32);
    if(!App::runningUnitTests()) {
        Storage::disk('images')->makeDirectory($directory);
    }

    return [
        'name' => $faker->text(40),
        'description' => $faker->text(100),
        'date_start' => $dateDeb,
        'date_end' => $dateFin,
        'path' => $directory,
        'user_id' => User::all()->random()->id,
    ];
});
