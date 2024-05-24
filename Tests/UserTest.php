<?php

namespace Tests;

use Controllers\AccountController;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class AccountControllerTest extends TestCase
{
    private $controller;
    private $reflection;

    protected function setUp(): void
    {
        // Création d'une instance de AccountController avec un argument fictif
        $this->controller = new AccountController("create");

        // Utilisation de la Réflexion pour accéder aux méthodes privées
        $this->reflection = new \ReflectionClass(AccountController::class);

        // Configuration de Mockery pour moquer la méthode statique createUser de UserRepository
        m::mock('overload:\Repositories\UserRepository')
            ->shouldReceive('createUser')
            ->once()
            ->withArgs(['testuser', m::type('string'), 'Test', 'User'])
            ->andReturn(true);

        // Configuration des variables POST nécessaires pour le test
        $_POST['login'] = 'testuser';
        $_POST['password'] = 'password123';
        $_POST['firstname'] = 'Test';
        $_POST['lastname'] = 'User';
    }

    public function testCreerUser()
    {
        // Accès à la méthode privée creerUser
        $method = $this->reflection->getMethod('creerUser');
        $method->setAccessible(true);

        // Exécution de la méthode privée
        $method->invoke($this->controller);

        // Assertions ou vérifications supplémentaires peuvent être ajoutées ici
    }

    protected function tearDown(): void
    {
        m::close();
        $_POST = []; // Nettoyer les variables globales après chaque test
    }
}
