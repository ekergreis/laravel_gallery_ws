<?php
/**
* Le controller LoginController permet de gérer les connexions utilisateurs
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Response;
use Illuminate\Support\Carbon;
use \Laravel\Passport\Http\Controllers\AccessTokenController as ATC;

// [OAUTH] Class Login héritant de AccessTokenController de Passport
class LoginController extends ATC
{
    /**
    * Connexion utilisateur
    * @bodyParam username string required E-mail de connexion
    * @bodyParam password string required Mot de passe de l'utilisateur
    * @bodyParam grant_type string required Type de connexion valeur = password
    * @bodyParam client_id string required Identifiant client fourni par Laravel Passport. Example: 2
    * @bodyParam client_secret string required Clé client fourni par Laravel Passport. Example: Hs9Jmsx0HDeOE4p9cHNefrLRlZI4vSgrdnjWlDgk
    * @bodyParam scope string required Privilège demandé valeur = *
    */
    public function issueToken(ServerRequestInterface $request)
    {
        try {
            // [OAUTH] Génération token
            $tokenResult = parent::issueToken($request);

            // [OAUTH] Recup infos token format json
            $content = $tokenResult->getContent();
            // [OAUTH] Informations Token json en tableau
            $data = json_decode($content, true);

            if(isset($data["error"]))
                throw new OAuthServerException('Codes acces incorrects.', 6, 'invalid_credentials', 401);

            // [OAUTH] Retours infos
            $infosRet=collect();
            $infosRet->put('access_token', $data['access_token']);
            $infosRet->put('token_type', 'Bearer');
            $infosRet->put('expires_at', Carbon::now()->addSeconds($data['expires_in']));

            return Response::json(array($infosRet)[0]);
        }
        catch (ModelNotFoundException $e) {
            return response(["message" => "Internal server error"], 500);
        }
        catch (OAuthServerException $e) {
            return response(["message" => "Codes acces incorrects.', 6, 'invalid_credentials"], 401);
        }
        catch (Exception $e) {
            return response(["message" => "Internal server error"], 500);
        }
    }
}
