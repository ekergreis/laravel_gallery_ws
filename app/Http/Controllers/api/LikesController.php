<?php
/**
* Le controller LikesController permet de gérer les likes sur images ou commentaires
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Classes\ {GestionUserInfos, GestionDelete};
use App\Http\Requests\ {LikePost, LikeGet};
use App\Models\Like;

class LikesController extends Controller
{
    /**
    * Ajout like
    * L'utilisateur indique qu'il aime ou n'aime plus une image
    * @bodyParam id_image integer required Identifiant de l'image (requis)
    */
    public function setLike(LikePost $request)
    {
        $request->validated();
        $gestionUser = new GestionUserInfos($request->user());

        $idImage = $request->id_image;
        // Si l'utilisateur connecté peut accéder à l'image
        if($gestionUser->canAccessImage($idImage)) {
            // Vérif si like existe
            $tLikes = Like::where('image_id', $idImage)->where('user_id', $gestionUser->user()->id)->get();
            if($tLikes->count() == 0) {
                // Like à activer
                $tLike = new Like();
                $tLike->image_id = $idImage;
                $tLike->user_id = $gestionUser->user()->id;
                $tLike->save();

                return response(['message' => __('gallery.like.add_success'), 'like' => true], 200);
            } else {
                // Like à désactiver
                $resultat = true;
                foreach($tLikes as $tLike) {
                    $gestionDelete = new GestionDelete($gestionUser->user());
                    if(!$gestionDelete->delLike($tLike)) $resultat = false;
                }
                if($resultat) return response(["message" => __('gallery.like.del_success'), 'like' => false], 200);
            }
        }

        return response(['message' => __('gallery.like.add_fail')], 400);
    }
}
