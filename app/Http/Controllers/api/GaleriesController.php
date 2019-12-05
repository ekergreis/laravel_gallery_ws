<?php
/**
* Le controller GaleriesController permet de gérer les galeries
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Classes\GestionDelete;
use App\Http\Requests\ {GaleriePost, GalerieGet};
use App\Models\ {Group, Galerie};

class GaleriesController extends Controller
{
    /**
    * Infos galeries
    * Retourne pour l'utilisateur connecté
    * les informations des galeries, groupes et amis (utilisateurs ayant groupe en commun)
    */
    public function getGaleries(Request $request)
    {
        $tabMenu = [];

        $tabMenu['Util']=$request->user()->name;

        $tabMenu['Groups'] = [];
        foreach($request->user()->group as $group) {
            $tabMenu['Groups'][]=['id' => $group->id,
                                'name' => $group->name
                                ];
        }

        $tabMenu['Friends'] = [];
        foreach($request->user()->getShareUser() as $shareUser) {
            $tabMenu['Friends'][]=['id' => $shareUser->id,
                                    'name' => $shareUser->name
                                    ];
        }

        $tabMenu['Galeries'] = [];
        foreach($request->user()->getShareGalerie() as $galerie) {
            $tabMenu['Galeries'][] = ['id' => $galerie->id,
                                    'name' => $galerie->name,
                                    'description' => $galerie->description,
                                    'count_images' => $galerie->nb_image,
                                    'date_start' => $galerie->date_start,
                                    'date_end' => $galerie->date_end,
                                    'create_by' => $galerie->user->name
                                    ];
        }

        return response()->json($tabMenu);
    }

    /**
    * Ajout galerie
    * Enregistre une nouvelle galerie liée à un ou des groupes
    * @bodyParam name string required Libellé de la galerie. Example: Vacances
    * @bodyParam descript string Description de la galerie. Example: Nous sommes partis à 4...
    * @bodyParam date_start date Date début. Example: 2019-07-01
    * @bodyParam date_end date Date fin. Example: 2019-07-20
    * @bodyParam group.*.id integer required Identifiants des groupes autorisés à accéder à la galerie
    */
    public function setGalerie(GaleriePost $request)
    {
        $request->validated();

        $dirGalerie=Str::random(32);

        $galerieAdd = new Galerie(['name' => $request->name,
                        'description' => $request->descript,
                        'date_start' => $request->date_start,
                        'date_end' => $request->date_end,
                        'path' => $dirGalerie,
                        'user_id' => $request->user()->id,
                        ]);

        $tabIDGroup = (collect($request->group)->pluck('id'));
        if($request->user()->canShareWithGroup($tabIDGroup)) {
            foreach($request->group as $GroupInGalerie) {
                Group::where('id', $GroupInGalerie['id'])->first()
                        ->galerie()->save($galerieAdd);
            }

            Storage::disk('images')->makeDirectory($dirGalerie);
            return response(["message" => "La galerie a été créée"], 200);

        } else {
            return response(["message" => "Groupe invalide"], 400);
        }
    }

    /**
    * Suppression galerie
    * Seul un admin ou le créateur de la galerie peuvent supprimer une galerie
    * @bodyParam id integer required Identifiant de la galerie
    */
    public function delGalerie(GalerieGet $request)
    {
        $request->validated();

        $tGalerie=Galerie::where('id', $request->id)->first();
        if($tGalerie) {
            $gestionDelete = new GestionDelete($request->user());
            $resultat = $gestionDelete->delGalerie($tGalerie);

            if($resultat) return response(["message" => "OK"], 200);
        }

        return response(["message" => "NOK"], 400);
    }
}
