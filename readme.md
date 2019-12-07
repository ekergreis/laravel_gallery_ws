# APIs de gestion de galeries photos

__Web service REST en Laravel 6 permettant de g�rer en backend des galeries photos avec acc�s utilisateur s�curis� avec OAuth (suivant projet ["ekergreis/secure_web_spa"](https://github.com/ekergreis/secure_web_spa).__

__Documentation du web service : /docs/ (chemin complet : ./public/docs/)__

- Les utilisateurs sont r�partis par groupe pour pouvoir acc�d�r aux galeries et images. 
- Pour emp�cher la r�cup�ration illicite d'images (par exemple en brute force), Les fichiers images de chaque galerie sont stock�s dans un r�pertoire token, et, le nom du fichier image est �galement sous forme de token (chaine al�atoire de 32 caract�res). 
- Des commentaires peuvent �tre enregistr�s pour les images. 
- Des "likes" peuvent �tre indiqu�s sur des images ou commentaires.


### R�cup�ration sources et installation :

	$ git clone https://ekergreis@bitbucket.org/ekergreis/laravel_gallery_ws.git
	$ composer install

Configurer le nom de votre base de donn�es dans le fichier .env
	
	$ php artisan migrate
	$ php artisan db:seed
	$ php artisan passport:install
    

# Enjoy !!!

*Auteur : Emmanuel Kergreis*
