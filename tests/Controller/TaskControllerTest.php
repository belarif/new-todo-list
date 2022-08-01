<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Tests\Fixtures\TodoListFunctionalTestCase;

final class TaskControllerTest extends TodoListFunctionalTestCase
{
    private const ROLE_ADMIN = 'ROLE_ADMIN';

    public function testItShouldDisplayTaskCreatePageWhenUserIsLogged()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $response = $client->sendRequest('GET', '/tasks/create');

        self::assertTrue($response->isOk());
    }

    public function testItShouldRedirectUserToLoginPageWhenTryToAccessingOnCreateTaskPageWithoutLogin()
    {
        $client = $this->createTodoListClient(true);
        $response = $client->sendRequest('GET', '/tasks/create');

        self::assertTrue($response->isRedirect('http://localhost/login'));
    }

    public function testItShouldCreateTask()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
        $client->sendRequest('GET', '/tasks/create');

        $logedUser = $client->getCurrentLoggedUser();

        $fixtures = $client->createFixtureBuilder();
        $task = $fixtures->task()
            ->createTask((new Task())->fromFixture($logedUser))
            ->getTask();

        $client->sendForm(
            'submit',
            [
                'task[title]' => $task->getTitle(),
                'task[content]' => $task->getContent(),
            ],
            'POST'
        );
        $client->redirectTo();

        self::assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a bien été créée.');
    }

    public function testItShouldDisplayTasksListDonePage()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);
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
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);

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
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);

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

    public function testItShouldUpdateTaskNotDone()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);

        $fixtures = $client->createFixtureBuilder();
        $logedUser = $client->getCurrentLoggedUser();

        $task = $fixtures->task()
            ->createTask((new Task())->fromFixture($logedUser))
            ->getTask();

        $client->sendRequest('GET', '/tasks/'.$task->getId().'/edit');

        $updateTask = $fixtures->task()
            ->loadFrom($task->getTitle())
            ->setContent('modified content')
            ->getTask();

        self::assertFalse($updateTask->isDone());

        $client->sendForm(
            'submit',
            [
                'task[title]' => $updateTask->getTitle(),
                'task[content]' => $updateTask->getContent(),
            ],
            'POST'
        );
        $client->redirectTo();

        self::assertSelectorTextContains('a.btn.btn-secondary', 'Retour à la liste des tâches faites');
    }

    public function testItShouldUpdateTaskDone()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);

        $fixtures = $client->createFixtureBuilder();
        $logedUser = $client->getCurrentLoggedUser();

        $task = $fixtures->task()
            ->createTask((new Task())->fromFixture($logedUser))
            ->getTask();

        $client->sendRequest('GET', '/tasks/'.$task->getId().'/edit');

        $updateTask = $fixtures->task()
            ->loadFrom($task->getTitle())
            ->setContent('modified content')
            ->setDone(true)
            ->getTask();

        self::assertTrue($updateTask->isDone());

        $client->sendForm(
            'submit',
            [
                'task[title]' => $updateTask->getTitle(),
                'task[content]' => $updateTask->getContent(),
            ],
            'POST'
        );
        $client->redirectTo();

        self::assertSelectorTextContains('a.btn.btn-secondary', 'Retour à la liste des tâches non faites');
    }

    public function testItShouldToggleTaskToDoneAndRedirectToTasksDonePage()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);

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

    public function testItShouldToggleTaskToNotDoneAndRedirectToTasksNotDonePage()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);

        $fixtures = $client->createFixtureBuilder();
        $logedUser = $client->getCurrentLoggedUser();

        $task = $fixtures->task()
            ->createTask((new Task())->fromFixture($logedUser))
            ->setDone(true)
            ->getTask();

        self::assertTrue($task->isDone());

        $response = $client->sendRequest('GET', '/tasks/'.$task->getId().'/toggle');

        self::assertTrue(!$task->isDone());
        self::assertTrue($response->isRedirect('/tasks_not_done'));
    }

    public function testItShouldDeleteTask()
    {
        $client = $this->createTodoListClientWithLoggedUser(true, self::ROLE_ADMIN);

        $fixtures = $client->createFixtureBuilder();
        $logedUser = $client->getCurrentLoggedUser();

        $task = $fixtures->task()
            ->createTask((new Task())->fromFixture($logedUser))
            ->getTask();
        self::assertNotNull($task->getId());

        $response = $client->sendRequest('GET', '/tasks/'.$task->getId().'/delete');
        self::assertNull($task->getId());

        self::assertTrue($response->isRedirect('/tasks_not_done'));
    }
}
