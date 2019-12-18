<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('api')->group(function () {
    // [OAUTH] Méthode login re-codé
    Route::post('login', 'LoginController@issueToken')->name('login');

    // [OAUTH] Routes accessible qu'après authentification avec token valide
    Route::group(['middleware' => 'auth:api'], function() {

        Route::get('galeries', 'GaleriesController@getGaleries')->name('auth.galeries_get');
        Route::get('images', 'ImagesController@getImages')->name('auth.images_get');
        Route::get('comments', 'CommentsController@getComments')->name('auth.comments_get');

        Route::post('galeries', 'GaleriesController@setGalerie')->name('auth.galerie_set');
        Route::post('images', 'ImagesController@setImage')->name('auth.image_set');
        Route::post('comments', 'CommentsController@setComment')->name('auth.comment_set');
        Route::post('like', 'LikesController@setLike')->name('auth.like_set');

        Route::delete('galeries', 'GaleriesController@delGalerie')->name('auth.galerie_del');
        Route::delete('images', 'ImagesController@delImage')->name('auth.image_del');
        Route::delete('comments', 'CommentsController@delComment')->name('auth.comment_del');

        // [OAUTH] [ROLE] uniquement par un utilisateur admin
        Route::middleware('can:accessAdminpanel')->group(function() {
            // [OAUTH] [ROLE] Inscription d'un nouvel utilisateur que pour les admins
            Route::post('signup', 'UserController@signup')->name('signup');

            Route::post('groups', 'GroupsController@setGroup')->name('auth.group_set');
            Route::delete('groups', 'GroupsController@delGroup')->name('admin.groups_del');
        });

        Route::get('logout', 'UserController@logout')->name('auth.logout');
    });
});


