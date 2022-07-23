<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Tests\Fixtures\TodoListFunctionalTestCase;

final class TaskControllerTest extends TodoListFunctionalTestCase
{
    public function testItShouldDisplayTaskCreatePageWhenUserIsLogged()
    {
        $client = $this->createTodoListClientWithLoggedUser();
        $response = $client->sendRequest('GET', '/tasks/create');

        self::assertTrue($response->isOk());
    }

    public function testItShouldRedirectUserToLoginPageWhenTryToAccessingOnCreateTaskPageWithoutLogin()
    {
        $client = $this->createTodoListClient(true);
        $response = $client->sendRequest('GET', '/tasks/create');

        self::assertTrue($response->isRedirect('http://localhost/login'));
    }

    public function testItShouldDisplayTasksListDonePage()
    {
        $client = $this->createTodoListClientWithLoggedUser();
        $fixtures = $client->createFixtureBuilder();

        $logedUser = $client->getCurrentLoggedUser();

        $fixtures->task()->createTask(Task::fromFixture($logedUser))->setDone(true);
        $fixtures->task()->createTask(Task::fromFixture($logedUser))->setDone(true);
        $fixtures->task()->createTask(Task::fromFixture($logedUser))->setDone(true);

        $response = $client->sendRequest('GET', '/tasks_done');
        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertNotNull($crawler->selectLink('Créer une tâche'));
    }

    public function testItShouldDisplayTasksListNotDonePage()
    {
        $client = $this->createTodoListClientWithLoggedUser();

        $fixtures = $client->createFixtureBuilder();

        $logedUser = $client->getCurrentLoggedUser();

        $fixtures->task()->createTask(Task::fromFixture($logedUser));
        $fixtures->task()->createTask(Task::fromFixture($logedUser));
        $fixtures->task()->createTask(Task::fromFixture($logedUser));

        $response = $client->sendRequest('GET', '/tasks_not_done');
        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertNotNull($crawler->selectLink('Créer une tâche'));
    }

    public function testItShouldDisplayEditTaskPage()
    {
        $client = $this->createTodoListClientWithLoggedUser();

        $fixtures = $client->createFixtureBuilder();
        $logedUser = $client->getCurrentLoggedUser();

        $task = $fixtures->task()
            ->createTask((new Task())->fromFixture($logedUser))
            ->getTask();

        $response = $client->sendRequest('GET', '/tasks/'.$task->getId().'/edit');

        $crawler = $client->getCrawler();

        self::assertTrue($response->isOk());
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('input[id=task_title]'));
        self::assertCount(1, $crawler->filter('textarea[id=task_content]'));
        self::assertNotNull($crawler->selectButton('submit'));
    }

    public function testItShouldToggleTask()
    {
        $client = $this->createTodoListClientWithLoggedUser();

        $fixtures = $client->createFixtureBuilder();
        $logedUser = $client->getCurrentLoggedUser();

        $task = $fixtures->task()
            ->createTask((new Task())->fromFixture($logedUser))
            ->getTask();

        self::assertTrue(!$task->isDone());

        $response = $client->sendRequest('GET', '/tasks/'.$task->getId().'/toggle');

        self::assertTrue($task->isDone());
        self::assertTrue($response->isRedirect('/tasks_done'));
    }

    public function testItShouldDeleteTask()
    {
        $client = $this->createTodoListClientWithLoggedUser();

        $fixtures = $client->createFixtureBuilder();
        $logedUser = $client->getCurrentLoggedUser();

        $task = $fixtures->task()
            ->createTask((new Task())->fromFixture($logedUser))
            ->getTask();

        $response = $client->sendRequest('GET', '/tasks/'.$task->getId().'/delete');

        self::assertTrue($response->isRedirect('/tasks_done'));
    }
}
