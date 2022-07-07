<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TaskControllerTest extends WebTestCase
{
    public function testItShouldDisplayTaskCreatePage()
    {
        $client = self::createClient();

        $client->loginUser(static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'user1']));

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_task_create'));

        $response = $client->getResponse();

        self::assertTrue($response->isOk());
        self::assertNotNull($crawler->selectLink('Retour à la liste des tâches'));
        self::assertCount(1, $crawler->filter('form'));
        self::assertNotNull($crawler->selectButton('submit'));
    }

    public function testItShouldDisplayTasksListDonePage()
    {
        $client = self::createClient();

        $client->loginUser(static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'user1']));

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_task_list_done'));

        $response = $client->getResponse();

        self::assertTrue($response->isOk());
        self::assertNotNull($crawler->selectLink('Créer une tâche'));
    }

    public function testItShouldDisplayTasksListNotDonePage()
    {
        $client = self::createClient();

        $client->loginUser(static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'user1']));

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_task_list_not_done'));

        $response = $client->getResponse();

        self::assertTrue($response->isOk());
        self::assertNotNull($crawler->selectLink('Créer une tâche'));
    }

    public function testItShouldDisplayEditTaskPage()
    {
        $client = self::createClient();

        $client->loginUser(static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'user1']));

        $crawler = $client->request('GET', '/tasks/1/edit');

        $response = $client->getResponse();

        self::assertTrue($response->isOk());
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('input[id=task_title]'));
        self::assertCount(1, $crawler->filter('textarea[id=task_content]'));
        self::assertNotNull($crawler->selectButton('submit'));
    }

    public function testItShouldToggleTask()
    {
        $client = self::createClient();

        $client->loginUser(static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'user1']));

        $urlGenerator = $client->getContainer()->get('router');

        $client->request('GET', '/tasks/1/toggle');

        $task = new Task();

        self::assertTrue(!$task->isDone());
        self::assertTrue($client->getResponse()->isRedirect($urlGenerator->generate('app_task_list_done')));
    }

    public function testItShouldDeleteTask()
    {
        $client = self::createClient();

        $client->loginUser(static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'admin1']));

        $urlGenerator = $client->getContainer()->get('router');

        $client->request('GET', '/tasks/1/delete');

        self::assertTrue($client->getResponse()->isRedirect($urlGenerator->generate('app_task_list_done')));
    }
}
