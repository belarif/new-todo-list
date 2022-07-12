<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testItShouldReturnValueIdProperty()
    {
        $task = new Task();

        self::assertNull($task->getId());
    }

    public function testItShouldInitializeCreatedDateProperty()
    {
        $task = new Task();

        self::assertNotNull($task->getCreatedAt());
        self::assertSame((new DateTime())->format('Y-m-d'), $task->getCreatedAt()->format('Y-m-d'));
    }

    public function testItShouldInitializeIsDoneProperty()
    {
        $task = new Task();

        self::assertFalse($task->isDone());
    }

    public function testItShouldUpdateTitleProperty()
    {
        $task = new Task();
        self::assertEmpty($task->getTitle());

        $task->setTitle('le titre');
        self::assertSame('le titre', $task->getTitle());
    }

    public function testItShouldUpdateContentProperty()
    {
        $task = new Task();
        self::assertEmpty($task->getContent());

        $task->setContent('le contenu de la tache');
        self::assertSame('le contenu de la tache', $task->getContent());
    }

    public function testItShouldReturnIsDonePropertyValue()
    {
        $task = new Task();

        self::assertNotNull($task->isDone());
    }

    public function testItShouldUpdateIsDoneProperty()
    {
        $task = new Task();

        self::assertFalse($task->isDone());
        $task->toggle(true);
        self::assertTrue((bool) $task->isDone());
    }
}
