<?php
/**
* Le controller LikesController permet de gérer les likes sur images ou commentaires
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Classes\GestionDelete;
use App\Http\Requests\ {LikePost, LikeGet};
use App\Models\Like;

class LikesController extends Controller
{
    /**
    * Ajout like
    * L'utilisateur indique qu'il aime une image ou un commentaire
    * @bodyParam id_image integer required Identifiant de l'image (requis si id_comment non renseigné)
    * @bodyParam id_comment integer required Identifiant du commentaire  (requis si id_image non renseigné)
    */
    public function setLike(LikePost $request)
    {
        $request->validated();

        $idImage = null;
        $idComment = null;

        $authCreate = false;
        if(!empty($request->id_image)) {
            $idImage = $request->id_image;
            $authCreate = $request->user()->canAccessImage($idImage);
        } else {
            if(!empty($request->id_comment)) {
                $idComment = $request->id_comment;
                $authCreate = $request->user()->canAccessComment($idComment);
            }
        }

        if($authCreate) {
            $tLike = new Like();
            $tLike->image_id = $idImage;
            $tLike->comment_id = $idComment;
            $tLike->user_id = $request->user()->id;
            $tLike->save();

            return response(['message' => 'OK'], 200);
        }

        return response(['message' => 'NOK'], 400);
    }

    /**
    * Suppression like
    * L'utilisateur annule son appréciation d'une image ou d'un commentaire
    * @bodyParam id integer required Identifiant du like
    */
    public function delLike(LikeGet $request)
    {
        $request->validated();

        $tLike=Like::where('id', $request->id)->first();
        if($tLike) {
            $gestionDelete = new GestionDelete($request->user());
            $resultat = $gestionDelete->delLike($tLike);

            if($resultat) return response(["message" => "OK"], 200);
        }

        return response(["message" => "NOK"], 400);
    }
}
