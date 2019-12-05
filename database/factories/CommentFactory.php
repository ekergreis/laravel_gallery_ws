<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use App\Models\Image;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'image_id' => Image::all()->random()->id,
        'comment' => $faker->text,
        'user_id' => User::all()->random()->id,
    ];
});
