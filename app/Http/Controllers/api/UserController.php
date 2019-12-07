<?php
/**
* Le controller UserController permet de gérer les ajout d'utilisateurs et les déconnexions
*
* @author Emmanuel Kergreis
*/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
    * Ajout utilisateur (admin)
    * @bodyParam name string required Nom de l'utilisateur
    * @bodyParam email string required E-mail de l'utilisateur
    * @bodyParam password string required Mot de passe de l'utilisateur
    */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
            ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json(['message' => __('gallery.user.add_success')], 201);
    }

    /**
    * Déconnexion utilisateur
    * Révocation du token
    */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => __('gallery.user.logout_success')]);
    }
}
