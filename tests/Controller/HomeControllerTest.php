<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HomeControllerTest extends WebTestCase
{
    public function testItShouldDisplayHomepage(): void
    {
        $client = self::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_homepage'));

        $response = $client->getResponse();

        self::assertTrue($response->isOk());
        self::assertSame(
            "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !",
            $crawler->filter('h1')->first()->text()
        );
    }
}
