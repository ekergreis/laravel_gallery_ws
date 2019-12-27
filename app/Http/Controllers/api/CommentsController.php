<?php
/**
* Le controller CommentsController permet de gérer les commentaires
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Classes\ {GestionUserInfos, GestionDelete};
use App\Http\Requests\ {ImageGet, CommentGet, CommentPost};
use App\Models\ {Image, Comment};

class CommentsController extends Controller
{
    /**
    * Infos commentaires
    * Les des commentaires renseignés pour une image
    * @bodyParam id integer required Identifiant de l'image
    */
    public function getComments(ImageGet $request)
    {
        $request->validated();
        $gestionUser = new GestionUserInfos($request->user());

        $tabMenu=[];

        if($gestionUser->canAccessImage($request->id)) {
            $tImage=Image::where('id', $request->id)->first();
            foreach($tImage->comment as $comment) {
                $me=false;
                if($gestionUser->user()->id == $comment->user_id) $me=true;

                $tabMenu[]=['id' => $comment->id,
                            'comment' => $comment->comment,
                            'create_by' => $comment->user->name,
                            'create_by_me' => $me,
                            ];
            }
        }
        return response()->json($tabMenu);
    }

    /**
    * Ajout commentaire
    * Un commentaire ne peut pas être re-créé à l'identique par le même utilisateur pour la même image
    * @bodyParam id integer required Identifiant de l'image
    * @bodyParam comment string required Texte de commentaire. Example: J'aime beaucoup cette photo
    */
    public function setComment(CommentPost $request)
    {
        $request->validated();
        $gestionUser = new GestionUserInfos($request->user());

        if($gestionUser->canAccessImage($request->id)) {
            $tComment = new Comment();
            $tComment->image_id = $request->id;
            $tComment->comment = $request->comment;
            $tComment->user_id = $gestionUser->user()->id;
            $tComment->save();

            return response(['message' => __('gallery.comment.add_success')], 200);
        }

        return response(['message' => __('gallery.comment.add_fail')], 400);
    }

    /**
    * Suppression commentaire
    * Seul un admin ou celui qui a saisi le commentaire peuvent le supprimer
    * @bodyParam id integer required Identifiant du commentaire
    */
    public function delComment(CommentGet $request)
    {
        $request->validated();

        $tComment=Comment::where('id', $request->id)->first();
        if($tComment) {
            $gestionDelete = new GestionDelete($request->user());
            $resultat = $gestionDelete->delComment($tComment);

            if($resultat) return response(['message' => __('gallery.comment.del_success')], 200);
        }

        return response(["message" => __('gallery.comment.del_fail')], 400);
    }
}
