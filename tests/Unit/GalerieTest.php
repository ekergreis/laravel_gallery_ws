<?php
/**
* [TESTS] GALERIES Tests unitaires des 3 méthodes d'accès aux apis api/galeries :
* GET Mauvaise authentification => FAIL 401
* GET Authentifié => SUCCESS 200
* POST Mauvaise authentification => FAIL 401
* POST Authentifié création galerie => SUCCESS 200
* POST Authentifié galerie en doublon => FAIL 422
* POST Authentifié user non autorisé pour groupe  => FAIL 422
* POST Authentifié user dates invalides => FAIL 422
* POST Authentifié groupe non renseigné => FAIL 422
* DELETE Mauvaise authentification => FAIL 401
* DELETE Authentifié id autorisé => SUCCESS 200
* DELETE Authentifié id non autorisé => FAIL 422
* DELETE Authentifié aucun id => FAIL 422
*/

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ {User, Group, Galerie};

class GalerieTest extends TestCase
{
    public function testGetGalerieBadAuth() {
        $reponse = $this->json('get', 'api/galeries');
        $reponse->assertStatus(401);
    }
    public function testGetGalerieAuth() {
        $user = User::all()->random(1)->first();
        $reponse = $this->actingAs($user, 'api')->json('get', 'api/galeries');

        $reponse->assertStatus(200);
        $reponse->assertJsonStructure([
            'Util',
            'Groups' => [ '*' => ['id', 'name']],
            'Friends' => [ '*' => ['id', 'name']],
            'Galeries' => [ '*' => ['id', 'name', 'description', 'count_images', 'date_start', 'date_end', 'create_by']]
        ]);
    }


    public function testSetGalerieBadAuth() {
        $reponse = $this->json('post', 'api/galeries');
        $reponse->assertStatus(401);
    }
    public function testSetGalerieAuth() {
        $user = User::all()->random(1)->first();
        $data = [
            'name' => 'galerie PHPUnit',
            'descript' => 'PHPUnit Test galerie',
            'date_start' => '2019-07-01',
            'date_end' => '2019-07-20',
        ];
        foreach($user->group as $group) {
            $data['group'][] = ['id' => $group->id];
        }

        $reponse = $this->actingAs($user, 'api')->json('post', 'api/galeries', $data);

        $reponse->assertStatus(200);
    }
    public function testSetGalerieDoublon() {
        $user = User::all()->random(1)->first();
        $galerie = Galerie::all()->random(1)->first();
        $data = [
            'name' => $galerie->name,
            'descript' => 'PHPUnit Test galerie',
            'date_start' => '2019-07-01',
            'date_end' => '2019-07-20',
        ];
        foreach($user->group as $group) {
            $data['group'][] = ['id' => $group->id];
        }

        $reponse = $this->actingAs($user, 'api')->json('post', 'api/galeries', $data);
        $reponse->assertStatus(422);
    }
    public function testSetGalerieBadGroup() {
        $user = User::all()->random(1)->first();
        $data = [
            'name' => 'galerie PHPUnit Bad Group',
            'descript' => 'PHPUnit Test galerie',
            'date_start' => '2019-07-01',
            'date_end' => '2019-07-20',
        ];

        $tGroup=Group::whereNotIn('id', $user->group->pluck('id'))->first();
        if($tGroup) $data['group'][] = ['id' => $tGroup->id];

        $reponse = $this->actingAs($user, 'api')->json('post', 'api/galeries', $data);
        $reponse->assertStatus(422);
    }
    public function testSetGalerieBadDate() {
        $user = User::all()->random(1)->first();
        $data = [
            'name' => 'galerie PHPUnit Bad Dates',
            'descript' => 'PHPUnit Test galerie',
            'date_start' => '01-07-2019',
            'date_end' => '2019-20-13',
        ];
        foreach($user->group as $group) {
            $data['group'][] = ['id' => $group->id];
        }

        $reponse = $this->actingAs($user, 'api')->json('post', 'api/galeries', $data);
        $reponse->assertStatus(422);
    }
    public function testSetGalerieNoGroup() {
        $user = User::all()->random(1)->first();
        $galerie = Galerie::all()->random(1)->first();
        $data = [
            'name' => 'galerie PHPUnit Bad Dates',
            'descript' => 'PHPUnit Test galerie',
            'date_start' => '01-07-2019',
            'date_end' => '2019-20-13',
        ];

        $reponse = $this->actingAs($user, 'api')->json('post', 'api/galeries', $data);
        $reponse->assertStatus(422);
    }


    public function testDelGalerieBadAuth() {
        $reponse = $this->json('delete', 'api/galeries');
        $reponse->assertStatus(401);
    }
    public function testDelGalerieAuth() {
        $data = [];
        $user = User::all()->random(1)->first();
        $tGalerie = Galerie::get();
        foreach($tGalerie as $galerie) {
            if($user->canAccessGalerie($galerie->id)) break;
        }
        if($galerie) $data = ['id' => $galerie->id];

        $reponse = $this->actingAs($user, 'api')->json('delete', 'api/galeries', $data);
        $reponse->assertStatus(200);
    }
    public function testDelGalerieBad() {
        $data = [];
        $user = User::all()->random(1)->first();
        $tGalerie = Galerie::get();
        foreach($tGalerie as $galerie) {
            if(!$user->canAccessGalerie($galerie->id)) break;
        }
        if($galerie) $data = ['id' => $galerie->id];

        $reponse = $this->actingAs($user, 'api')->json('delete', 'api/galeries', $data);
        $reponse->assertStatus(422);
    }
    public function testDelGalerieNotId() {
        $data = [];
        $user = User::all()->random(1)->first();
        $reponse = $this->actingAs($user, 'api')->json('delete', 'api/galeries', $data);
        $reponse->assertStatus(422);
    }
}
