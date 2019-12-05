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
    private $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Supprimer une galerie
     * @param collection galerie
     * @return bool
     */
    public function delGalerie($tGalerie)
    {
        if($tGalerie->user_id == $this->user->id || $this->user->role('admin')) {
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
     * @param collection image
     * @param boolean indique si la suppression est déclenché à un niveau supérieur (galerie)
     * @return bool
     */
    public function delImage($tImage, $supprCascade=false)
    {
        if($supprCascade || $tImage->user_id == $this->user->id || $this->user->role('admin')) {

            $tComments=$tImage->comment()->get();
            foreach($tComments as $tComment) {
                $this->delComment($tComment, true);
            }

            $tLikes=$tImage->like()->get();
            foreach($tLikes as $tLike) {
                $this->delLike($tLike, true);
            }

            //dump('Image : '.$tImage->id);
            Storage::disk('images')->delete($tImage->galerie->path.'/'.$tImage->filename);
            $tImage->delete();

            return true;
        }
        return false;
    }

    /**
     * Supprimer un commentaire
     * @param collection commentaire
     * @param boolean indique si la suppression est déclenché à un niveau supérieur (image)
     * @return bool
     */
    public function delComment($tComment, $supprCascade=false)
    {
        if($supprCascade || $tComment->user_id == $this->user->id || $this->user->role('admin')) {

            $tLikes=$tComment->like()->get();
            foreach($tLikes as $tLike) {
                $this->delLike($tLike, true);
            }

            //dump('Comment : '.$tComment->id);
            $tComment->delete();

            return true;
        }
        return false;
    }

    /**
     * Supprimer un like
     * @param collection commentaire
     * @param boolean indique si la suppression est déclenché à un niveau supérieur (image)
     * @return bool
     */
    public function delLike($tLike, $supprCascade=false)
    {
        if($supprCascade || $tLike->user_id == $this->user->id || $this->user->role('admin')) {
            //dump('Like : '.$tLike->id);
            $tLike->delete();

            return true;
        }
        return false;
    }
}
