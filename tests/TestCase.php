<?php
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory;
use \Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    // [TESTS] Initialisation tests unitaires
    // [TESTS] Modif phpunit.xml pour appliquer migration sur base sqlite en mémoire
    use CreatesApplication, DatabaseMigrations;

    protected $faker;

    public function setUp() : void {
        parent::setUp();

        $this->faker = Factory::create();
        // [TESTS] Génération des clés pour token dans base sqlite
        $this->artisan('passport:install');
        $this->artisan('db:seed');
    }

}
