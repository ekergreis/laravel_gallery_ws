<?php
/**
* Le controller ImagesController permet de gérer les images
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Classes\GestionDelete;
use App\Http\Requests\ {GalerieGet, ImagePost, ImageGet};
use App\Models\ {Galerie, Image};

class ImagesController extends Controller
{
    /**
    * Infos images
    * Liste des images accessibles dans une galerie
    * @bodyParam id integer required Identifiant de la galerie
    */
    public function getImages(GalerieGet $request)
    {
        $request->validated();

        $tabMenu=[];

        if($request->user()->canAccessGalerie($request->id)) {
            $tGalerie=Galerie::where('id', $request->id)->first();
            $tImages=$tGalerie->image()->get();

            $tabMenu['dir']=$tGalerie->path;
            foreach($tImages as $image) {
                $tabMenu['img'][]=['id' => $image->id,
                        'filename' => $image->filename,
                        'comment_count' => $image->nb_comment,
                        'like_count' => $image->nb_like,
                        'create_by' => $image->user->name,
                        ];
            }
        }

        return response()->json($tabMenu);
    }

    /**
    * Ajout image
    * Une image ne peut être ajoutée qu'une fois dans une galerie (vérification doublon par checksum)
    * @bodyParam id integer required Identifiant de la galerie
    * @bodyParam extension string required Extension du fichier image (limitée aux formats : jpg, jpeg, png)
    * @bodyParam data string required Image encodée en base64
    */
    public function setImage(ImagePost $request)
    {
        $request->validated();

        if($request->user()->canAccessGalerie($request->id)) {
            $extensionImg = $request->extension;
            $dataImg = base64_decode($request->data);
            if(!empty($dataImg)) {
                $checksumImg = crc32($dataImg);

                $tGalerie=Galerie::where('id', $request->id)->first();
                if($tGalerie->image()->where('checksum', $checksumImg)->count()==0) {
                    $nameImg = strtolower(Str::random(32)).'.'.$extensionImg;

                    Storage::disk('images')->put($tGalerie->path.'/'.$nameImg, $dataImg);

                    if(Storage::disk('images')->has($tGalerie->path.'/'.$nameImg)>0) {
                        if(Storage::disk('images')->size($tGalerie->path.'/'.$nameImg)>0) {
                            $tImage = new Image();
                            $tImage->galerie_id = $tGalerie->id;
                            $tImage->filename = $nameImg;
                            $tImage->checksum = $checksumImg;
                            $tImage->user_id = $request->user()->id;
                            $tImage->save();

                            return response(["message" => __('gallery.image.add_success')], 200);
                        }
                    }
                }
                return response(["message" => __('gallery.image.add_fail_exist')], 400);
            }
        }
        return response(["message" => __('gallery.image.add_fail')], 400);
    }

    /**
    * Suppression image
    * Seul un admin ou celui qui a ajouté l'image peuvent la supprimer
    * @bodyParam id integer required Identifiant de l'image
    */
    public function delImage(ImageGet $request)
    {
        $request->validated();

        $tImage=Image::where('id', $request->id)->first();
        if($tImage) {
            $gestionDelete = new GestionDelete($request->user());
            $resultat = $gestionDelete->delImage($tImage);

            if($resultat) return response(["message" => __('gallery.image.del_success')], 200);
        }

        return response(["message" => __('gallery.image.del_fail')], 400);
    }
}
