<?php

namespace App\Tests\Controller;

use App\Tests\Fixtures\TodoListFunctionalTestCase;

final class HomeControllerTest extends TodoListFunctionalTestCase
{
    public function testItShouldDisplayHomepage(): void
    {
        $client = $this->createTodoListClient(false);

        $response = $client->sendRequest('GET', '/');

        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertSame(
            "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !",
            $crawler->filter('h1')->first()->text()
        );
    }
}
