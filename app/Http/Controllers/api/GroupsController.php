<?php
/**
* Le controller GroupsController permet de gérer les groupes
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Http\Requests\ {GroupPost, GroupGet};
use App\Models\ {Group, User};

class GroupsController extends Controller
{
    /**
    * Ajout groupe (admin)
    * Enregistre un nouveau groupe lié un ou des utilisateurs
    * @bodyParam name string required Libellé du groupe. Example: Famille
    * @bodyParam usergroup.*.id integer Identifiants des utilisateurs autorisés à accéder au groupe
    */
    public function setGroup(GroupPost $request)
    {
        $request->validated();

        $groupAdd = new Group(['name' => $request->name]);
        $request->user()->group()->save($groupAdd);

        if(!empty($request->usergroup)) {
            foreach($request->usergroup as $UserInGroup) {
                if($request->user()->canShareWithUser($UserInGroup['id'])) {
                    $userAdd=User::where('id', $UserInGroup['id'])->first();
                    $userAdd->group()->save($groupAdd);
                }
            }
        }

        return response(["message" => "Le groupe a été créé"], 200);
    }

    /**
    * Suppression groupe (admin)
    * @bodyParam id integer required Identifiant du groupe à supprimer.
    */
    public function delGroup(GroupGet $request) {

        $request->validated();

        Group::findOrFail($request->id)->delete();
        return response(["message" => "Le groupe a été supprimé"], 200);
    }

}
