<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoListFunctionalTestCase extends WebTestCase
{
    public function createTodoListClientWithLoggedUser(bool $transactional = true, $userRole): TodoListClient
    {
        $client = $this->createTodoListClient($transactional);
        $fixtures = $client->createFixtureBuilder();

        $role = $fixtures->role()
            ->createRole((new Role())->fromFixture())
            ->setRoleName($userRole)
            ->getRole();

        $user = $client->createFixtureBuilder()
            ->user()
            ->createUser((new User())->fromFixture())
            ->addRole($role)
            ->getUser();

        $client->loginUser($user);

        return $client;
    }

    protected function createTodoListClient(bool $transactional): TodoListClient
    {
        $client = new TodoListClient(parent::createClient());

        return $client->startSession($transactional);
    }
}
