<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gallery Messages retournées par les APIs
    |--------------------------------------------------------------------------
    */
    'login' => [
        'server_error' => 'Erreur serveur',
        'user_error' => 'Codes d\'accès incorrects',
    ],
    'user' => [
        'add_success' => 'Utilisateur ajouté',
        'logout_success' => 'Utilisateur déconnecté',
    ],
    'group' => [
        'add_success' => 'Groupe ajouté',
        'add_fail' => 'Echec enregistrement groupe',
        'del_success' => 'Groupe supprimé',
        'del_fail' => 'Echec suppression groupe',
        'id_fail' => 'Groupe introuvable',
        'doublon' => 'Groupe déjà enregistré',
        'invalid_name' => 'Nom du groupe invalide',
    ],
    'galerie' => [
        'add_success' => 'Galerie ajoutée',
        'add_fail' => 'Echec enregistrement galerie',
        'del_success' => 'Galerie supprimée',
        'del_fail' => 'Echec suppression galerie',
        'id_fail' => 'Galerie introuvable',
        'doublon' => 'Galerie déjà enregistrée',
        'invalid_name' => 'Nom de la galerie invalide',
    ],
    'image' => [
        'add_success' => 'Image ajoutée',
        'add_trait_error' => 'Erreur traitement de l\'image',
        'add_fail_exist' => 'Image déjà existante dans la galerie',
        'add_fail' => 'Echec enregistrement image',
        'del_success' => 'Image supprimée',
        'del_fail' => 'Echec suppression image',
        'id_fail' => 'Image introuvable',
        'invalid_extension' => 'Format de l\'image invalide',
        'invalid_data' => 'Données de l\'image invalides',
    ],
    'comment' => [
        'add_success' => 'Commentaire ajouté',
        'add_fail' => 'Echec enregistrement commentaire',
        'del_success' => 'Commentaire supprimé',
        'del_fail' => 'Echec suppression commentaire',
        'id_fail' => 'Commentaire introuvable',
        'doublon' => 'Commentaire déjà enregistré',
        'invalid' => 'Commentaire invalide',
    ],
    'like' => [
        'add_success' => 'Like',
        'add_fail' => 'Echec Like',
        'del_success' => 'Not Like',
        'invalid' => 'Like invalide',
    ],
];
