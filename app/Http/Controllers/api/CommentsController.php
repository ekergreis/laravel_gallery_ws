<?php
/**
* Le controller CommentsController permet de gérer les commentaires
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Classes\GestionDelete;
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

        $tabMenu=[];
        if($request->user()->canAccessImage($request->id)) {
            $tImage=Image::where('id', $request->id)->first();
            foreach($tImage->comment as $comment) {
                $tabMenu[]=['id' => $comment->id,
                            'comment' => $comment->comment,
                            'like' => $comment->nb_like,
                            'create_by' => $comment->user->name,
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

        if($request->user()->canAccessImage($request->id)) {
            $tComment = new Comment();
            $tComment->image_id = $request->id;
            $tComment->comment = $request->comment;
            $tComment->user_id = $request->user()->id;
            $tComment->save();

            return response('', 200);
        }

        return response('', 400);
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

            if($resultat) return response(["message" => "OK"], 200);
        }

        return response(["message" => "NOK"], 400);
    }
}
