<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TaskControllerTest extends WebTestCase
{
    public function testItShouldDisplayTaskCreatePage()
    {
        $client = self::createClient();

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

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_task_list_done'));

        $response = $client->getResponse();

        self::assertTrue($response->isOk());
        self::assertNotNull($crawler->selectLink('Créer une tâche'));
    }

    public function testItShouldDisplayTasksListNotDonePage()
    {
        $client = self::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_task_list_not_done'));

        $response = $client->getResponse();

        self::assertTrue($response->isOk());
        self::assertNotNull($crawler->selectLink('Créer une tâche'));
    }

    public function testItShouldDisplayEditTaskPage()
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/tasks/6/edit');

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

        $urlGenerator = $client->getContainer()->get('router');

        $client->request('GET', '/tasks/5/toggle');

        $task = new Task();

        self::assertTrue(!$task->isDone());
        self::assertTrue($client->getResponse()->isRedirect($urlGenerator->generate('app_task_list_done')));
    }

    public function testItShouldDeleteTask()
    {
        $client = self::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $client->request('GET', '/tasks/7/delete');

        self::assertTrue($client->getResponse()->isRedirect($urlGenerator->generate('app_task_list_done')));
    }
}
