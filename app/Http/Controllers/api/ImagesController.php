<?php
/**
* Le controller ImagesController permet de gérer les images
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Classes\ {GestionUserInfos, GestionDelete, TraitementImages};
use App\Http\Requests\ {GalerieGet, ImagePost, ImageGet};
use App\Models\ {Galerie, Image};

class ImagesController extends Controller
{
    /**
    * Infos images
    * Liste des images accessibles dans une galerie
    * @bodyParam id integer required Identifiant de la galerie. Requis si aucun id image
    * @bodyParam img.*.id integer required identifiants des images à afficher (pour best of)
    */
    public function getImages(GalerieGet $request)
    {
        $request->validated();
        $gestionUser = new GestionUserInfos($request->user());

        $tabMenu=[];

        if(!empty($request->id)) {
            // Chargement d'une galerie (id renseigné)
            // Si l'utilisateur connecté a le droit d'accéder à la galerie
            if($gestionUser->canAccessGalerie($request->id)) {
                $tGalerie=Galerie::where('id', $request->id)->first();

                //Infos de la galerie
                $tabMenu['name'] = $tGalerie->name;
                $tabMenu['description'] = $tGalerie->description;
                $tabMenu['date_start'] = $tGalerie->date_start->format(config('gallery.date_format_export'));
                $tabMenu['date_end'] = $tGalerie->date_end->format(config('gallery.date_format_export'));

                // Infos images de la galerie
                $tImages=$tGalerie->image()->get()->sortByDesc(function($image){
                    return $image->nb_like;
                });
                foreach($tImages as $image) {
                    $tabMenu['img'][] = $this->ConstructInfosImages($image, $gestionUser->user()->id);
                }

                // Infos groupes de l'utilisateur connecté ayant accès à la galerie
                $tGroup = $tGalerie->group;
                foreach($tGroup as $group) {
                    if($gestionUser->canShareWithGroup([$group->id])) {
                        $tabMenu['groups'][] = $group->name;
                    }
                }
            }
        } else {
            // Chargement d'une liste d'image (best of)
            foreach($request->img as $idImage) {
                if($gestionUser->canAccessImage($idImage)) {
                    $image = Image::where('id', $idImage)->first();
                    $tabMenu['img'][] = $this->ConstructInfosImages($image, $gestionUser->user()->id);
                }
            }
        }

        return response()->json($tabMenu);
    }

     /**
     * Fonction de formatage des informations images pour export JSON
     * @param \App\Models\Image $image
     * @return Array
     */
    private function ConstructInfosImages($image, $idUser)
    {
        if(!empty($image->filename)) {
            return ['id' => $image->id,
                'dir' => $image->galerie->path,
                'mini_filename' => config('gallery.miniature_prefixe_name').$image->filename,
                'filename' => $image->filename,
                'comment_count' => $image->nb_comment,
                'like_count' => $image->nb_like,
                'like_user' => $image->getUserLike($idUser),
                'create_by' => $image->user->name,
            ];
        }
        return [];
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
        $gestionUser = new GestionUserInfos($request->user());

        // Vérification si l'utilisateur connecté à l'accès à la galerie
        if($gestionUser->canAccessGalerie($request->id)) {
            $extensionImg = $request->extension;
            $tabInfosImg = explode('data:image/', $request->data);
            $tabInfosImg = explode(';base64,', $tabInfosImg[1]);
            $extensionImg = $tabInfosImg[0];
            $dataImg = $tabInfosImg[1];

            $dataImg = base64_decode($dataImg);
            if (!empty($dataImg)) {
                // Vérification checksum inexistant
                $checksumImg = crc32($dataImg);
                $tGalerie = Galerie::where('id', $request->id)->first();
                if ($tGalerie->image()->where('checksum', $checksumImg)->count() == 0) {
                    // Construction nom de l'image (token)
                    $nameImg = strtolower(Str::random(config('gallery.img_token_lenght'))) . '.' . $extensionImg;

                    // Enregistrement du fichier dans le dossier de la galerie
                    Storage::disk('images')->put($tGalerie->path . '/' . $nameImg, $dataImg);

                    // Vérification existence du fichier
                    if (Storage::disk('images')->has($tGalerie->path . '/' . $nameImg) > 0) {
                        // Vérification taille du fichier
                        if (Storage::disk('images')->size($tGalerie->path . '/' . $nameImg) > 0) {

                            // Traitement images pour création miniature
                            $traitImg = new TraitementImages();
                            if ($traitImg->creationMiniature(Storage::disk('images')->path($tGalerie->path), $nameImg)) {

                                //Enregistrement de l'image dans la base
                                $tImage = new Image();
                                $tImage->galerie_id = $tGalerie->id;
                                $tImage->filename = $nameImg;
                                $tImage->checksum = $checksumImg;
                                $tImage->user_id = $gestionUser->user()->id;
                                $tImage->save();

                                return response([
                                    'message' => __('gallery.image.add_success'),
                                    'id' => $tImage->id,
                                    'dir' => $tGalerie->path,
                                    'mini_filename' => config('gallery.miniature_prefixe_name').$nameImg,
                                    'filename' => $nameImg,
                                    'createBy' => $request->user()->name,
                                ], 200);
                            }
                        }
                    }
                    return response(["message" => __('gallery.image.add_trait_error')], 400);
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

        // Vérification existence de l'image dans la base
        $tImage=Image::where('id', $request->id)->first();
        if($tImage) {
            // Appel classe gérant les suppressions
            $gestionDelete = new GestionDelete($request->user());
            $resultat = $gestionDelete->delImage($tImage);

            if($resultat) return response(["message" => __('gallery.image.del_success')], 200);
        }

        return response(["message" => __('gallery.image.del_fail')], 400);
    }
}
