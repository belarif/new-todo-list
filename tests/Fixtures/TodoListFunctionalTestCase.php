<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoListFunctionalTestCase extends WebTestCase
{
    public function createTodoListClientWithLoggedUser(bool $transactional = true): TodoListClient
    {
        $client = $this->createTodoListClient($transactional);
        $fixtures = $client->createFixtureBuilder();

        $role = $fixtures->role()
            ->createRole(Role::fromFixture())
            ->setRoleName('ROLE_ADMIN')
            ->getRole();

        $user = $client->createFixtureBuilder()
            ->user()
            ->createUser(User::fromFixture())
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
