<?php

namespace App\Providers;

// [OAUTH]
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Classes\GestionUserInfos;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // [OAUTH] Initialise les routes auto Passport nécessitant une authentification (en remplacement login)
        // [OAUTH] Ces routes ont été re-codés dans le Controller AuthController
        //Passport::routes();

        // [ROLE] Autorisation pour roles admin
        Gate::define('accessAdminpanel', function($user) {
            $gestionUser = new GestionUserInfos($user);
            return $gestionUser->CtrlRole(['admin']);
        });

         // [ROLE] Autorisation pour roles standard
        Gate::define('accessStandard', function($user) {
            $gestionUser = new GestionUserInfos($user);
            return $gestionUser->CtrlRole(['standard', 'admin']);
        });
    }
}
