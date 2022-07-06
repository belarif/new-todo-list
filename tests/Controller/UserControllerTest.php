<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
{
    public function testItShouldDisplayUserCreatePage()
    {
        $client = self::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_user_create'));

        $response = $client->getResponse();
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
        $client = self::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_user_list'));

        $response = $client->getResponse();
        self::assertTrue($response->isOk());
        self::assertSame('Liste des utilisateurs', $crawler->filter('h1')->first()->text());
    }

    public function testItShouldDisplayUserEditPage()
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/users/1/edit');

        $response = $client->getResponse();
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
        $client = self::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $client->request('GET', '/users/17/delete');

        self::assertTrue($client->getResponse()->isRedirect($urlGenerator->generate('app_user_list')));
    }
}
