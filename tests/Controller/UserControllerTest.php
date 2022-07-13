<?php

namespace App\Tests\Controller;

use App\Tests\Fixtures\TodoListFunctionalTestCase;

final class UserControllerTest extends TodoListFunctionalTestCase
{
    public function testItShouldDisplayUserCreatePage()
    {
        $client = $this->createTodoListClientWithLoggedUser();

        $response = $client->sendRequest('GET', '/users/create');

        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('input[id=user_username]'));
        self::assertCount(1, $crawler->filter('input[id=user_password_first]'));
        self::assertCount(1, $crawler->filter('input[id=user_password_second]'));
        self::assertCount(1, $crawler->filter('input[id=user_email]'));
        self::assertNotNull($crawler->selectButton('submit'));
    }

    public function testItShouldDisplayUsersListPage()
    {
        $client = $this->createTodoListClientWithLoggedUser();

        $response = $client->sendRequest('GET', '/users');

        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertSame('Liste des utilisateurs', $crawler->filter('h1')->first()->text());
    }

    public function testItShouldDisplayUserEditPage()
    {
        $client = $this->createTodoListClientWithLoggedUser();

        $response = $client->sendRequest('GET', '/users/'.$client->getCurrentLoggedUser()->getId().'/edit');

        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('input[id=user_username]'));
        self::assertCount(1, $crawler->filter('input[id=user_password_first]'));
        self::assertCount(1, $crawler->filter('input[id=user_password_second]'));
        self::assertCount(1, $crawler->filter('input[id=user_email]'));
        self::assertNotNull($crawler->selectButton('submit'));
    }

    public function testItShouldDeleteUser()
    {
        $client = $this->createTodoListClientWithLoggedUser();

        $response = $client->sendRequest('GET', '/users/'.$client->getCurrentLoggedUser()->getId().'/delete');

        self::assertTrue($response->isRedirect('/users'));
    }
}
