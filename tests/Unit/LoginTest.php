<?php
// [TESTS] Tests unitaires de l'API login
namespace Tests\Unit;

//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\OauthClients;

class LoginTest extends TestCase
{
    public function testClientSecretExist() {
        $tabParam=[];
        $this->assertTrue($this->getClientSecret($tabParam));
    }

    public function testCanLogin() {
        $user = factory(User::class)->create(['password' => bcrypt('good')]);
        $tabParam=['username' => $user->email, 'password' => 'good'];
        $this->getClientSecret($tabParam);
        $reponse = $this->json('post', 'api/login', $tabParam);
        $reponse->assertStatus(200);
        $reponse->assertJsonStructure(['access_token', 'token_type', 'expires_at']);
    }
    public function testBadLoginPassword() {
        $user = factory(User::class)->create(['password' => bcrypt('test')]);
        $tabParam=['username' => $user->email, 'password' => 'bad'];
        $this->getClientSecret($tabParam);
        $reponse = $this->json('post', 'api/login', $tabParam);
        $reponse->assertStatus(401);
    }
    public function testBadLoginUser() {
        $tabParam=['username' => 'test@testeur.com', 'password' => 'bad'];
        $this->getClientSecret($tabParam);
        $reponse = $this->json('post', 'api/login', $tabParam);
        $reponse->assertStatus(401);
    }
    public function testBadClientSecret() {
        $user = factory(User::class)->create(['password' => bcrypt('good')]);
        $tabParam=['username' => $user->email, 'password' => 'good'];
        $this->getClientSecret($tabParam);
        $tabParam['client_secret'] = 'bad_client_secret';
        $reponse = $this->json('post', 'api/login', $tabParam);
        $reponse->assertStatus(401);
    }

    private function getClientSecret(&$tabParam) {
        $tOAuthClient = OauthClients::where('password_client', 1)->first();
        if(!empty($tOAuthClient->secret)) {
            $tabParam['grant_type'] = 'password';
            $tabParam['client_id'] = $tOAuthClient->id;
            $tabParam['client_secret'] = $tOAuthClient->secret;
            $tabParam['scope"'] = '*';
            return true;
        }
        return false;
    }
}
