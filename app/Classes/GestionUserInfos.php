<?php
/**
* La classe CtrlAccesUser permet d'identifier les autorisations d'accès
*
* @author Emmanuel Kergreis
*/

namespace App\Classes;

use App\Models\ {User, Galerie, Image, Comment};

class GestionUserInfos
{
    private $user;
    /**
     * Initialisation de la classe
     * @param \App\Models\User $user
     * @return bool
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Récupérer la collection user
     * @return \App\Models\User
     */
    public function user() {
        return $this->user;
    }

    /**
     * [ROLE] Vérifier si l'utilisateur apartient à un groupe particulier
     * @param string|array $role
     * @return bool
     */
    public function CtrlRole($role=[]) {
        $role = (array)$role;
        if(empty($role)) return true;
        return in_array($this->user->role, $role);
    }

    /**
     * Retrouver la liste des utilisateurs avec lesquels le partage est autorisé
     * @return \Illuminate\Database\Eloquent\Collection User
     */
    public function getShareUser() {
        if($this->CtrlRole('admin')) {
            $tShareUser = User::orderBy('name')->get();
        } else {
            $groupsUser = $this->user->group->pluck('id');
            $tShareUser = User::where('id', '<>', $this->user->id)->whereHas('group', function($q) use($groupsUser) {
                        $q->whereIn('group_id', $groupsUser);
                    })->orderBy('name')
                    ->get();
        }
        return $tShareUser;
    }
    /**
    * Retrouver la liste des galeries sur lesquelles le partage est autorisé
    * @return \Illuminate\Database\Eloquent\Collection Galerie
    */
    public function getShareGalerie() {
        if($this->CtrlRole('admin')) {
            $tShareGalerie = Galerie::orderBy('date_start', 'desc')->orderBy('date_end', 'desc')->get();
        } else {
            $groupsUser = $this->user->group->pluck('id');
            $tShareGalerie = Galerie::whereHas('group', function($q) use($groupsUser) {
                            $q->whereIn('group_id', $groupsUser);
                        })->orderBy('date_start', 'desc')->orderBy('date_end', 'desc')
                        ->get();
        }
        return $tShareGalerie;
    }


    /**
    * Identifier si le partage avec un user est autorisé
    * @param integer $idShareUser
    * @return bool
    */
    public function canShareWithUser($idShareUser) {
        if($this->CtrlRole('admin')) return true;
        if(!empty(User::find($idShareUser))) {
            $tabGroupShareUser = User::find($idShareUser)->group->pluck('id');
            return $this->user->group()->whereIn('group_id', $tabGroupShareUser)->count() > 0 ? true : false;
        }
        return false;
    }
    /**
    * Identifier si le partage avec un (ou plusieurs) group est autorisé
    * @param array $tabIDGroup
    * @return bool
    */
    public function canShareWithGroup($tabIDGroup, $ctrlRole = true) {
        if($ctrlRole && $this->CtrlRole('admin')) return true;
        return $this->user->group()->whereIn('group_id', $tabIDGroup)->count() == count($tabIDGroup) ? true : false;
    }
    /**
    * Identifier si l'accès à une galerie est autorisé
    * @param integer $idGalerie
    * @return bool
    */
    public function canAccessGalerie($idGalerie) {
        if($this->CtrlRole('admin')) return true;
        foreach($this->user->group as $group) {
            foreach($group->galerie as $galerie) {
                if($galerie->id == $idGalerie) return true;
            }
        }
        return false;
    }
    /**
    * Identifier si l'accès à une image est autorisé
    * @param integer $idImage
    * @return bool
    */
    public function canAccessImage($idImage) {
        if($this->CtrlRole('admin')) return true;
        $tImage = Image::where('id', $idImage)->first();
        if(!empty($tImage)) {
            if($this->canAccessGalerie($tImage->galerie_id)) return true;
        }
        return false;
    }
    /**
    * Identifier si l'accès à un commentaire est autorisé
    * @param integer $idComment
    * @return bool
    */
    public function canAccessComment($idComment) {
        if($this->CtrlRole('admin')) return true;
        $tComment = Comment::where('id', $idComment)->first();
        if(!empty($tComment)) {
            if($this->canAccessImage($tComment->image_id)) return true;
        }
        return false;
    }
}
