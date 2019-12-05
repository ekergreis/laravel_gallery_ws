<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        // [OAUTH] Désactivation vérification format JSON pour requete sans body (param dans headers)
        /*
        if (! $request->expectsJson()) {
            return route('login');
        }
        */
    }
}
