<?php
/*
|--------------------------------------------------------------------------
| Variables de configuration des web services Gallery
|--------------------------------------------------------------------------
*/
return [
    // Nombre de meilleures images à afficher en page d'acceuil
    'nb_best_img' => 3,

    //Format d'export des dates
    'date_format_export' => 'd/m/Y',

    // Taille des tokens nom des répertoires et images
    'dir_token_lenght' => 32,
    'img_token_lenght' => 32,

    // Paramètres de traitement des images pour génération miniatures
    'miniature_prefixe_name' => 'small_',
    'miniature_width' => null,
    'miniature_height' => 200,
];
