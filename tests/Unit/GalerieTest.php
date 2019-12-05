<?php
// [TESTS] Tests unitaires de l'API menu
namespace Tests\Unit;

//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class GalerieTest extends TestCase
{
    public function testGalerieBadAuth() {
        $reponse = $this->json('get', 'api/galeries');
        $reponse->assertStatus(401);
    }
    public function testCanGalerieAuth() {
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
}
