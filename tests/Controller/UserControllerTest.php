<?php

namespace App\Tests\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Tests\Fixtures\TodoListFunctionalTestCase;

final class UserControllerTest extends TodoListFunctionalTestCase
{
    private const ROLE_ADMIN = 'ROLE_ADMIN';

    public function testItShouldAccessOnCreateUserPageWhenUserIsLogged()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $response = $client->sendRequest('GET', '/users/create');

        self::assertTrue($response->isOk());
    }

    public function testItShouldRedirectUserToLoginPageWhenTryToAccessingOnCreateUserPageWithoutLogin()
    {
        $client = $this->createTodoListClient(true);
        $response = $client->sendRequest('GET', '/users/create');

        self::assertTrue($response->isRedirect('http://localhost/login'));
    }

    public function testItShouldCreateUser(): void
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $fixtures = $client->createFixtureBuilder();

        $user = $fixtures->user()
            ->loadFrom($username = uniqid('username-'))
            ->getUser();
        self::assertNull($user);

        $response = $client->sendRequest(
            'POST',
            '/users/create',
            [
                'user_create' => [
                    'username' => $username,
                    'password' => [
                        'first' => $password = uniqid('password'),
                        'second' => $password,
                    ],
                    'email' => $email = uniqid().'@todolist.com',
                ],
            ]
        );
        self::assertTrue($response->isRedirect());
        self::assertNotNull(
            $newUser = $fixtures->user()
                ->loadFrom($username)
                ->getUser()
        );
        self::assertSame($email, $newUser->getEmail());
    }

    public function testItShouldRedirectUserWhenTryingToCreateExistingUser(): void
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $fixtures = $client->createFixtureBuilder();

        $user = $fixtures->user()
            ->createUser(User::fromFixture())
            ->getUser();
        self::assertNotNull($user);

        $response = $client->sendRequest(
            'POST',
            '/users/create',
            [
                'user_create' => [
                    'username' => $user->getUsername(),
                    'password' => [
                        'first' => $password = uniqid('password'),
                        'second' => $password,
                    ],
                    'email' => uniqid().'@todolist.com',
                ],
            ]
        );
        self::assertTrue($response->isRedirect('/users/create'));
    }

    public function testItShouldDisplayUsersListPage()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $fixtures = $client->createFixtureBuilder();

        $fixtures->user()->createUser(User::fromFixture());
        $fixtures->user()->createUser(User::fromFixture());
        $fixtures->user()->createUser(User::fromFixture());

        $response = $client->sendRequest('GET', '/users');
        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertSame('Liste des utilisateurs', $crawler->filter('h1')->first()->text());
        self::assertGreaterThan(3, $crawler->filter('tbody tr')->count());
    }

    public function testItShouldDisplayUserEditPage()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $fixtures = $client->createFixtureBuilder();

        $user = $fixtures
            ->user()
            ->createUser(User::fromFixture())
            ->getUser();

        self::assertNotEmpty($user->getId());

        $response = $client->sendRequest('GET', '/users/'.$user->getId().'/edit');
        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('select[id=user_edit_role]'));
        self::assertNotNull($crawler->selectButton('submit'));
    }

    public function testItShouldUpdateUser(): void
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $fixtures = $client->createFixtureBuilder();

        $user = $fixtures->user()
            ->createUser(User::fromFixture())
            ->getUser();

        $role = $fixtures->role()
            ->createRole(Role::fromFixture())
            ->getRole();

        $response = $client->sendRequest(
            'POST',
            '/users/'.$user->getId().'/edit',
            [
                'user_edit' => [
                    'role' => [
                        $role->getId(),
                    ],
                ],
            ]
        );
        self::assertTrue($response->isRedirect('/users'));
    }

    public function testItShouldDeleteUser()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $fixtures = $client->createFixtureBuilder();

        $user = $fixtures->user()
            ->createUser(User::fromFixture())
            ->getUser();

        self::assertNotNull($user->getId());

        $response = $client->sendRequest('GET', '/users/'.$user->getId().'/delete');

        self::assertNull($user->getId());
        self::assertTrue($response->isRedirect('/users'));
    }
}
