<?php
/**
* La classe TraitementImages permet de transformer les images uploadées
*
* @author Emmanuel Kergreis
*/

namespace App\Classes;

use Image;
use Intervention\Image\Exception\NotReadableException;

class TraitementImages
{
    public function creationMiniature($path, $imageName, $width, $height)
    {
         //re-dimensioner pour miniature hauteur fixée à 250px largeur auto (ratio)
        try {
            $img = Image::make($path.'/'.$imageName);
        }
        catch(NotReadableException $e) {
            return false;
        }
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->orientate()->save($path.'/small_'.$imageName);

        return true;
    }
}
