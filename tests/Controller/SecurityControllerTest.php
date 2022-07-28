<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Fixtures\TodoListFunctionalTestCase;

final class SecurityControllerTest extends TodoListFunctionalTestCase
{
    private const ROLE_USER = 'ROLE_USER';

    public function testItShouldDisplayLoginPage(): void
    {
        $client = $this->createTodoListClient(false);

        $response = $client->sendRequest('GET', '/login');

        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('input[name=_username]'));
        self::assertCount(1, $crawler->filter('input[name=_password]'));
        self::assertNotNull($crawler->selectButton('submit'));
    }

    public function testItShouldLoginTheUser()
    {
        $client = $this->createTodoListClient(true);
        $client->sendRequest('GET', '/login');

        $fixtures = $client->createFixtureBuilder();
        $user = $fixtures
            ->user()
            ->createUser((new User())->fromFixture())
            ->getUser();

        $client->sendForm(
            'submit',
            [
                '_username' => $user->getUsername(),
                '_password' => $user->getPassword(),
            ],
            'POST'
        );
        $client->loginUser($user);
        $client->redirectTo();

        self::assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    public function testItShouldDisplayUnauthorizedAccessPageToUsersWhoDoNotHaveAccess()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_USER);

        $response = $client->sendRequest('GET', '/users');

        self::assertTrue($response->isRedirect('/access_denied'));
    }

    public function testItShouldDisplayAccessDeniedPage()
    {
        $client = $this->createTodoListClient(false);

        $response = $client->sendRequest('GET', '/access_denied');

        self::assertTrue($response->isOk());
    }
}
