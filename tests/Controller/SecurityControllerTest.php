<?php

namespace App\Tests\Controller;

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
}
