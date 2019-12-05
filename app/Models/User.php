<?php
// [OAUTH] Modèle vers table user
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// [OAUTH] Lier modèle avec Passport
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    // [OAUTH] Lier modèle avec Passport
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsToMany(Group::Class)->withTimestamps();
    }


    /**
     * [ROLE] Vérifier si l'utilisateur apartient à un groupe particulier
     * @param string|array $role
     * @return bool
     */
    public function role($role=[]) {
        $role = (array)$role;
        if(empty($role)) return true;
        return in_array($this->role, $role);
    }

    /**
     * Retrouver la liste des utilisateurs avec lesquels le partage est autorisé
     * @return collection users
     */
    public function getShareUser() {
        if($this->role('admin')) {
            $tShareUser = User::orderBy('name')->get();
        } else {
            $groupsUser = $this->group->pluck('id');
            $tShareUser = User::where('id', '<>', $this->id)->whereHas('group', function($q) use($groupsUser) {
                        $q->whereIn('group_id', $groupsUser);
                    })->orderBy('name')
                    ->get();
        }
        return $tShareUser;
    }
    /**
    * Retrouver la liste des galeries sur lesquelles le partage est autorisé
    * @return collection galeries
    */
    public function getShareGalerie() {
        if($this->role('admin')) {
            $tShareGalerie = Galerie::orderBy('date_start', 'desc')->orderBy('date_end', 'desc')->get();
        } else {
            $groupsUser = $this->group->pluck('id');
            $tShareGalerie = Galerie::whereHas('group', function($q) use($groupsUser) {
                            $q->whereIn('group_id', $groupsUser);
                        })->orderBy('date_start', 'desc')->orderBy('date_end', 'desc')
                        ->get();
        }
        return $tShareGalerie;
    }


    /**
    * Identifier si le partage avec un user est autorisé
    * @param id user
    * @return bool
    */
    public function canShareWithUser($idShareUser) {
        if($this->role('admin')) return true;
        if(!empty(User::find($idShareUser))) {
            $tabGroupShareUser = User::find($idShareUser)->group->pluck('id');
            return $this->group()->whereIn('group_id', $tabGroupShareUser)->count() > 0 ? true : false;
        }
        return false;
    }
    /**
    * Identifier si le partage avec un (ou plusieurs) group est autorisé
    * @param array id group
    * @return bool
    */
    public function canShareWithGroup($tabIDGroup) {
        if($this->role('admin')) return true;
        return $this->group()->whereIn('group_id', $tabIDGroup)->count() == count($tabIDGroup) ? true : false;
    }
    /**
    * Identifier si l'accès à une galerie est autorisé
    * @param array id galerie
    * @return bool
    */
    public function canAccessGalerie($idGalerie) {
        if($this->role('admin')) return true;
        foreach($this->group as $group) {
            foreach($group->galerie as $galerie) {
                if($galerie->id == $idGalerie) return true;
            }
        }
        return false;
    }
    /**
    * Identifier si l'accès à une image est autorisé
    * @param array id image
    * @return bool
    */
    public function canAccessImage($idImage) {
        if($this->role('admin')) return true;
        $tImage = Image::where('id', $idImage)->first();
        if(!empty($tImage)) {
            if($this->canAccessGalerie($tImage->galerie_id)) return true;
        }
        return false;
    }
    /**
    * Identifier si l'accès à un commentaire est autorisé
    * @param array id commentaire
    * @return bool
    */
    public function canAccessComment($idComment) {
        if($this->role('admin')) return true;
        $tComment = Comment::where('id', $idComment)->first();
        if(!empty($tComment)) {
            if($this->canAccessImage($tComment->image_id)) return true;
        }
        return false;
    }
}
