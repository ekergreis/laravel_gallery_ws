<?php
/**
* Le controller GroupsController permet de gérer les groupes
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Classes\GestionUserInfos;
use App\Http\Requests\ {GroupPost, GroupGet};
use App\Models\ {Group, User};

class GroupsController extends Controller
{
    /**
    * Infos user et groupes (admin)
    */
    public function getInfoGroupUser(Request $request)
    {
        $tabGroups = [];
        $gestionUser = new GestionUserInfos($request->user());

        // Récupération des informations groupes / users
        $tGroup = Group::query()->get();
        if($tGroup->count() > 0) {
            foreach($tGroup as $group) {
                $tabGroups['Groups'][] = [
                    'id' => $group->id,
                    'name' => $group->name,
                    'users' => $group->user()->orderBy('users.id')->pluck('users.id'),
                ];
            }
        }

        // Détail des users pour rapprochement groupes
        $tUser = $gestionUser->getShareUser();
        if($tUser->count() > 0) {
            foreach($tUser as $user) {
                $tabGroups['Users'][] = [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
            }
        }

        return $tabGroups;
    }

    /**
    * Ajout groupe (admin)
    * Enregistre un nouveau groupe lié un ou des utilisateurs
    * Ou affecte un ou des utilisateurs à un groupe existant
    * @bodyParam id string required Id du groupe. Requis sans name
    * @bodyParam name string required Libellé du groupe. Requis sans Id. Example: Famille
    * @bodyParam usergroup.*.id integer Identifiants des utilisateurs autorisés à accéder au groupe
    */
    public function setGroup(GroupPost $request)
    {
        $request->validated();
        $gestionUser = new GestionUserInfos($request->user());

        $idGroup = false;
        if(!empty($request->id)) {
            // Affectation users à un groupe existant
            if($gestionUser->canShareWithGroup([$request->id])) {
                // L'utilisateur connecté a le droit de partager avec le groupe sélectionné
                $idGroup=$request->id;
            }
        } else {
            // Création nouveau groupe
            $groupAdd = new Group(['name' => $request->name]);
            // Affectation par défaut au user de création
            $gestionUser->user()->group()->save($groupAdd);
            $idGroup = Group::where('name', $request->name)->first()->id;
        }

        // Affectation des groupes aux users sélectionnés
        if($idGroup) {
            if(!empty($request->usergroup)) {
                //Désactivation lien de tous les users avec le groupe
                Group::where('id', $idGroup)->first()->user()->detach();

                foreach($request->usergroup as $UserInGroup) {
                    if($gestionUser->canShareWithUser($UserInGroup['id'])) {
                        // L'utilisateur connecté a le droit de partager avec le user sélectionné
                        $userAdd=User::where('id', $UserInGroup['id'])->first();
                        $gestionUserAdd = new GestionUserInfos($userAdd);
                        if(!$gestionUserAdd->canShareWithGroup([$idGroup], false)) {
                            // Affectation user s'il n'a pas déjà l'accès au groupe
                            $userAdd->group()->attach([$idGroup]);
                        }
                    }
                }
            }

            return response(["message" => __('gallery.group.add_success')], 200);
        }

        return response(["message" => __('gallery.group.add_fail')], 400);
    }

    /**
    * Suppression user d'un groupe (admin)
    * @bodyParam id integer required Identifiant du groupe à supprimer.
    * @bodyParam usergroup.*.id integer Identifiants des utilisateurs à supprimer du groupe
    */
    public function delGroupUser(GroupPost $request) {

        $request->validated();
        $gestionUser = new GestionUserInfos($request->user());

        if(!empty($request->id)) {
            if($gestionUser->canShareWithGroup([$request->id])) {
                // L'utilisateur connecté a le droit de partager avec le groupe sélectionné
                $idGroup=$request->id;

                if(!empty($request->usergroup)) {
                    foreach($request->usergroup as $UserInGroup) {
                        if($gestionUser->canShareWithUser($UserInGroup['id'])) {
                            // L'utilisateur connecté a le droit de partager avec le user sélectionné
                            $userDel=User::where('id', $UserInGroup['id'])->first();
                            $gestionUserDel = new GestionUserInfos($userDel);
                            if($gestionUserDel->canShareWithGroup([$idGroup])) {
                                // Le user selectionné avait bien accès au groupe, il faut désactiver le lien
                                $userDel->group()->detach($idGroup);
                            }
                        }
                    }
                    return response(["message" => __('gallery.group.del_user_success')], 200);
                }
            }
        }

        return response(["message" => __('gallery.group.del_user_fail')], 400);
    }

    /**
    * Suppression groupe (admin)
    * @bodyParam id integer required Identifiant du groupe à supprimer.
    */
    public function delGroup(GroupGet $request) {

        $request->validated();

        Group::findOrFail($request->id)->delete();
        return response(["message" => __('gallery.group.del_success')], 200);
    }

}
