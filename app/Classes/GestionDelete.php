<?php
/**
* La classe GestionDelete permet de gérer les suppressions
*
* @author Emmanuel Kergreis
*/

namespace App\Classes;

use Illuminate\Support\Facades\Storage;

class GestionDelete
{
    private $gestionUser;
    /**
     * Initialisation de la classe
     * @param \App\Models\User $user
     * @return bool
     */
    public function __construct($user)
    {
        $this->gestionUser = new GestionUserInfos($user);
    }

    /**
     * Supprimer une galerie
     * @param \App\Models\Galerie $tGalerie
     * @return bool
     */
    public function delGalerie($tGalerie)
    {
        if($tGalerie->user_id == $this->gestionUser->user()->id || $this->gestionUser->CtrlRole('admin')) {
            $tImages=$tGalerie->image()->get();
            foreach($tImages as $tImage) {
                $this->delImage($tImage, true);
            }

            //dump('Galerie : '.$tGalerie->id);
            Storage::disk('images')->deleteDirectory($tGalerie->path);
            $tGalerie->delete();

            return true;
        }
        return false;
    }

    /**
     * Supprimer une image
     * @param \App\Models\Image $tImage
     * @param boolean $supprCascade Indicateur suppression déclenchée à un niveau supérieur (galerie)
     * @return bool
     */
    public function delImage($tImage, $supprCascade=false)
    {
        if($supprCascade || $tImage->user_id == $this->gestionUser->user()->id || $this->gestionUser->CtrlRole('admin')) {

            $tComments=$tImage->comment()->get();
            foreach($tComments as $tComment) {
                $this->delComment($tComment, true);
            }

            $tLikes=$tImage->like()->get();
            foreach($tLikes as $tLike) {
                $this->delLike($tLike, true);
            }

            //dump('Image : '.$tImage->id);
            Storage::disk('images')->delete($tImage->galerie->path.'/small_'.$tImage->filename);
            Storage::disk('images')->delete($tImage->galerie->path.'/'.$tImage->filename);
            $tImage->delete();

            return true;
        }
        return false;
    }

    /**
     * Supprimer un commentaire
     * @param \App\Models\Comment $tComment
     * @param boolean $supprCascade Indicateur suppression déclenchée à un niveau supérieur (image)
     * @return bool
     */
    public function delComment($tComment, $supprCascade=false)
    {
        if($supprCascade || $tComment->user_id == $this->gestionUser->user()->id || $this->gestionUser->CtrlRole('admin')) {
            //dump('Comment : '.$tComment->id);
            $tComment->delete();

            return true;
        }
        return false;
    }

    /**
     * Supprimer un like
     * @param \App\Models\Like $tLike
     * @param boolean $supprCascade Indicateur suppression déclenchée à un niveau supérieur (image)
     * @return bool
     */
    public function delLike($tLike, $supprCascade=false)
    {
        if($supprCascade || $tLike->user_id == $this->gestionUser->user()->id || $this->gestionUser->CtrlRole('admin')) {
            //dump('Like : '.$tLike->id);
            $tLike->delete();

            return true;
        }
        return false;
    }
}
