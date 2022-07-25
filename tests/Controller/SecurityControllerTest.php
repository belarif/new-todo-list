<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Fixtures\TodoListFunctionalTestCase;

final class SecurityControllerTest extends TodoListFunctionalTestCase
{
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

        $response = $client->sendRequest('GET', '/login');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Se connecter')->form();

        $fixtures = $client->createFixtureBuilder();

        $user = $fixtures
            ->user()
            ->createUser((new User())->fromFixture())
            ->getUser();

        $form['_username'] = $user->getUsername();
        $form['_password'] = $user->getPassword();

        $client->submitForm($form);

        $client->redirectTo();

        self::assertTrue($response->isRedirect());
        self::assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }
}
