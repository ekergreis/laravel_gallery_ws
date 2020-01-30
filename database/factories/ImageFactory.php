<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;

use App\Classes\TraitementImages;
use App\Models\Image;
use App\Models\Galerie;
use App\Models\User;
use Faker\Generator as Faker;

use StanDaniels\ImageGenerator\Canvas;
use StanDaniels\ImageGenerator\Color;
use StanDaniels\ImageGenerator\Image as CreatingImage;
use StanDaniels\ImageGenerator\Shape\Shape;

$factory->define(Image::class, function (Faker $faker) {
    $galerie=Galerie::all()->random();
    if(App::runningUnitTests()) {
        $filename=Str::random(config('gallery.img_token_lenght'));
    } else {
        // Recherche sur Lorempixel
        $filename = $faker->image('public/storage/galeries_images/'.$galerie->path, 800, 600, null, false);
        if(empty($filename)) {
            // Génération image aléatoire si Lorempixel ne répond pas
            $filename = Str::random(config('gallery.img_token_lenght')).'.png';
            $transparency = random_int(60, 80) / 100;
            $canvas = Canvas::create(800, 600, 2)
                ->background(Color::random($transparency));
            for ($i = random_int(100, 150); $i > 0; $i--) {
                $transparency = random_int(60, 80) / 100;
                Shape::random($canvas, Color::random($transparency))->draw();
            }
            CreatingImage::create($canvas, 'public/storage/galeries_images/'.$galerie->path.'/'.$filename);
        }

        dump('Image filename ('.$galerie->id.'): '.$galerie->path.'/'.$filename);
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
