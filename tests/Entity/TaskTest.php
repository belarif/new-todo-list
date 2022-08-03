<?php

namespace App\Tests\Entity;

use App\Entity\Builder\TaskBuilder;
use App\Entity\Task;
use PHPUnit\Framework\TestCase;
use Throwable;

class TaskTest extends TestCase
{
    public function testItShouldThrowExceptionWhenTryAccessToNotInitializePropertyId()
    {
        $task = TaskBuilder::newTask();

        self::expectException(Throwable::class);
        self::expectExceptionMessage(sprintf(
            'Typed property %s::$id must not be accessed before initialization',
            Task::class,
        ));

        $task->getTask()->getId();
    }

    public function testItShouldInitializeCreatedDateProperty()
    {
        $task = TaskBuilder::newTask()
            ->getTask();

        self::assertNotNull($task->getCreatedAt());
    }

    public function testItShouldInitializeIsDoneProperty()
    {
        $task = TaskBuilder::newTask()
            ->getTask();

        self::assertFalse($task->isDone());
    }

    public function testItShouldHydrateTitleProperty()
    {
        $task = TaskBuilder::newTask()
            ->setTitle(uniqid())
            ->getTask();

        self::assertNotNull($task->getTitle());
    }

    public function testItShouldHydrateContentProperty()
    {
        $task = TaskBuilder::newTask()
            ->setContent(uniqid())
            ->getTask();

        self::assertNotNull($task->getContent());
    }

    public function testItShouldReturnIsDonePropertyValue()
    {
        $task = TaskBuilder::newTask()
            ->getTask();

        self::assertNotNull($task->isDone());
    }

    public function testItShouldToggleIsDoneProperty()
    {
        $task = TaskBuilder::newTask()
            ->getTask();
        self::assertFalse($task->isDone());

        $task->toggle(true);
        self::assertTrue($task->isDone());
    }
}
