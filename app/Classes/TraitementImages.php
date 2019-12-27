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
    public function creationMiniature($path, $imageName)
    {
        //re-dimensioner pour miniature hauteur fixée dans config/gallery.php
        $width = config('gallery.miniature_width');
        $height = config('gallery.miniature_height');
        $prefixeName = config('gallery.miniature_prefixe_name');

        try {
            $img = Image::make($path.'/'.$imageName);
        }
        catch(NotReadableException $e) {
            return false;
        }
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->orientate()->save($path.'/'.$prefixeName.$imageName);

        return true;
    }
}
