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
use Illuminate\Support\Carbon;

use App\Classes\ {GestionUserInfos, GestionDelete};
use App\Http\Requests\ {GaleriePost, GalerieGet};
use App\Models\ {Group, Galerie, Image};

class GaleriesController extends Controller
{
    /**
    * Infos galeries
    * Retourne pour l'utilisateur connecté
    * les informations des galeries, groupes, podium best of images
    */
    public function getGaleries(Request $request)
    {
        $gestionUser = new GestionUserInfos($request->user());

        $tabMenu = [];

        // Récupération nom user connecté
        $tabMenu['Util']=$gestionUser->user()->name;

        // Récupération groupes accessibles par user connecté (pour création galerie)
        $tabMenu['Groups'] = [];
        foreach($gestionUser->user()->group as $group) {
            $tabMenu['Groups'][]=['id' => $group->id,
                                'name' => $group->name
                                ];
        }

        // Récupération des galeries accessibles
        $tabMenu['Galeries'] = [];
        $tabGalerieID = [];
        foreach($gestionUser->getShareGalerie() as $galerie) {

            $tabGalerieID[] = $galerie->id;

            $tabMenu['Galeries'][] = ['id' => $galerie->id,
                                    'name' => $galerie->name,
                                    'description' => $galerie->description,
                                    'count_images' => $galerie->nb_image,
                                    'date_start' => $galerie->date_start->format(config('gallery.date_format_export')),
                                    'date_end' => $galerie->date_end->format(config('gallery.date_format_export')),
                                    'create_by' => $galerie->user->name,
                                    ];
        }

         // Récupération des images bestof dans galeries accessibles
        $tabMenu['BestImgs'] = [];
        $tImages = Image::whereIn('galerie_id', $tabGalerieID)->get();
        $tImages = $tImages->sortByDesc(function($image) {
            return $image->nb_like;
        });
        foreach($tImages as $image) {
            $tabMenu['BestImgs'][] = ['id' => $image->id];
            if(count($tabMenu['BestImgs']) == config('gallery.nb_best_img')) break;
        }

        /*
        $tabMenu['Friends'] = [];
        foreach($gestionUser->getShareUser() as $shareUser) {
            $tabMenu['Friends'][]=['id' => $shareUser->id,
                                    'name' => $shareUser->name
                                    ];
        }
        */

        return response()->json($tabMenu);
    }

    /**
    * Ajout galerie
    * Enregistre une nouvelle galerie liée à un ou des groupes
    * @bodyParam name string required Libellé de la galerie. Example: Vacances
    * @bodyParam descript string Description de la galerie. Example: Nous sommes partis à 4...
    * @bodyParam date_start date Date début. Example: 01/07/2019
    * @bodyParam date_end date Date fin. Example: 20/07/2019
    * @bodyParam group.*.id integer required Identifiants des groupes autorisés à accéder à la galerie
    */
    public function setGalerie(GaleriePost $request)
    {
        $request->validated();
        $gestionUser = new GestionUserInfos($request->user());

        // Génération nom dossier de la galerie à créer (token)
        $dirGalerie=Str::random(config('gallery.dir_token_lenght'));

        // Préparation enregistrement de la galerie
        $galerieAdd = new Galerie(['name' => $request->name,
                        'description' => $request->descript,
                        'date_start' => Carbon::createFromFormat('d/m/Y', $request->date_start),
                        'date_end' => Carbon::createFromFormat('d/m/Y', $request->date_end),
                        'path' => $dirGalerie,
                        'user_id' => $gestionUser->user()->id,
                        ]);

        // Parcours des groupes affectés et création / liaison de la galerie
        // Vérification si les id groupes sélectionnés sont accessibles
        if($gestionUser->canShareWithGroup($request->group)) {
            foreach($request->group as $GroupInGalerie) {
                Group::where('id', $GroupInGalerie['id'])->first()
                        ->galerie()->save($galerieAdd);
            }

            $tGalerie = Galerie::where('name', $request->name)->first();
            if($tGalerie) {
                // Création dossier de la galerie
                Storage::disk('images')->makeDirectory($dirGalerie);
                return response([
                    "message" => __('gallery.galerie.add_success'),
                    "id" => $tGalerie->id,
                ], 200);
            }
        } else {
            return response(["message" => __('gallery.galerie.add_fail')], 400);
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

        // Vérification existence de la galerie
        $tGalerie=Galerie::where('id', $request->id)->first();
        if($tGalerie) {
            $gestionDelete = new GestionDelete($request->user());
            $resultat = $gestionDelete->delGalerie($tGalerie);

            if($resultat) return response(["message" => __('gallery.galerie.del_success')], 200);
        }

        return response(["message" => __('gallery.galerie.del_fail')], 400);
    }
}
