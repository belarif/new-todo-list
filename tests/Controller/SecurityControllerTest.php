<?php

namespace App\Tests\Controller;

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
