# APIs de gestion de galeries photos

__Web service REST en Laravel 6 permettant de gérer en backend des galeries photos avec accès utilisateur sécurisé avec OAuth (suivant projet ["ekergreis/secure_web_spa"](https://github.com/ekergreis/secure_web_spa).__

__Documentation du web service : /docs/ (chemin complet : ./public/docs/)__

- Les utilisateurs sont répartis par groupe pour pouvoir accéder aux galeries et images. 
- Pour empêcher la récupération illicite d'images (par exemple en brute force), Les fichiers images de chaque galerie sont stockés dans un répertoire token, et, le nom du fichier image est également sous forme de token (chaine aléatoire de 32 caractéres). 
- Des commentaires peuvent étre enregistrés pour les images. 
- Des "likes" peuvent étre indiqués sur des images ou commentaires.


### Récupération sources et installation :

	$ git clone https://ekergreis@bitbucket.org/ekergreis/laravel_gallery_ws.git
	$ composer install

Configurer le nom de votre base de données dans le fichier .env
	
	$ php artisan migrate
	$ php artisan db:seed
	$ php artisan passport:install
    

# Enjoy !!!

*Auteur : Emmanuel Kergreis*
