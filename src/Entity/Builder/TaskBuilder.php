<?php

namespace App\Entity\Builder;

use App\Entity\Task;

class TaskBuilder
{
    private Task $task;

    public function __construct()
    {
        $this->task = new Task();
    }

    public function setTitle(String $title): self
    {
        $this->task->setTitle($title);

        return $this;
    }

    public function setContent(String $content): self
    {
        $this->task->setContent($content);

        return $this;
    }

    public static function newTask(): self
    {
        return new self();
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function toggle($flag)
    {
        $this->task->toggle($flag);
    }
}