# APIs de gestion de galeries photos

__Web service REST en Laravel 6 permettant de gérer en backend des galeries photos avec accès utilisateur sécurisé avec OAuth (suivant projet ["ekergreis/secure_web_spa"](https://github.com/ekergreis/secure_web_spa).__

__Documentation des 17 apis du web service : /docs/ (chemin complet : ./public/docs/)__

- Les utilisateurs sont répartis par groupe pour pouvoir accéder aux galeries et images. 
- Pour empêcher la récupération illicite d'images (par exemple en brute force), Les fichiers images de chaque galerie sont stockés dans un répertoire token, et, le nom du fichier image est également sous forme de token (chaine aléatoire de 32 caractéres). 
- Des commentaires peuvent étre enregistrés pour les images.
- Des "likes" peuvent étre indiqués sur des images. Les likes permettent de sélectionner les meilleures images à afficher sur la page d'accueil.


### Récupération sources et installation :

	$ git clone https://github.com/ekergreis/laravel_gallery_ws.git
	$ composer install

Configurer le nom de votre base de données dans le fichier .env
	
	$ php artisan migrate
	$ php artisan db:seed
	$ php artisan passport:install

Des tests unitaires ont été réalisés (apis auth et galeries) et le code a été analysé avec Larastan (adaptation de PHPStan pour Laravel).

    $ vendor/bin/phpunit
    $ php artisan code:analyse

Le frontend réalisé en Vue.JS (Quasar) est en cours de développement.
Le projet est accessible sur GitHub : ["ekergreis/vue_gallery_frontend"](https://github.com/ekergreis/vue_gallery_frontend).__

![Galerie](https://raw.githubusercontent.com/ekergreis/vue_gallery_frontend/master/img/demo_galerie.png)

*Auteur : Emmanuel Kergreis*
