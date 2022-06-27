<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function test_it_should_return_value_id_property()
    {
        $task = new Task();

        self::assertNull($task->getId());
    }

    public function test_it_should_initialize_created_date_property()
    {
        $task = new Task();

        self::assertNotNull($task->getCreatedAt());
        self::assertSame((new DateTime())->format('Y-m-d'), $task->getCreatedAt()->format('Y-m-d'));
    }

    public function test_it_should_initialize_is_done_property()
    {
        $task = new Task();

        self::assertFalse($task->isDone());
    }

    public function test_it_should_update_title_property()
    {
        $task = new Task();
        self::assertEmpty($task->getTitle());

        $task->setTitle('le titre');
        self::assertSame('le titre', $task->getTitle());
    }

    public function test_it_should_update_content_property()
    {
        $task = new Task();
        self::assertEmpty($task->getContent());

        $task->setContent('le contenu de la tache');
        self::assertSame('le contenu de la tache', $task->getContent());
    }

    public function test_it_should_return_is_done_property_value()
    {
        $task = new Task();

        self::assertNotNull($task->isDone());
    }

    public function test_it_should_update_is_done_property()
    {
        $task = new Task();

        self::assertFalse($task->isDone());
        $task->toggle(true);
        self::assertTrue((bool)$task->isDone());
    }
}
