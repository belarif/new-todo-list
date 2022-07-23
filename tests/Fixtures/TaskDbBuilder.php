<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class TaskDbBuilder
{
    private Task $task;
    private ContainerInterface $container;
    private EntityManagerInterface $entityManager;
    private TaskRepository $taskRepository;
    private DateTime $createdAt;
    private bool $isDone;

    public function __construct(Task $task, TaskRepository $taskRepository, ContainerInterface $container)
    {
        $this->task = $task;
        $this->container = $container;
        $this->taskRepository = $taskRepository;
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->createdAt = new DateTime();
        $this->isDone = false;
    }

    public function setTitle(string $title): self
    {
        $this->task->setTitle($title);
        $this->save();

        return $this;
    }

    public function setContent(string $content): self
    {
        $this->task->setContent($content);
        $this->save();

        return $this;
    }

    public function setDone(bool $done): self
    {
        $this->task->toggle($done);
        $this->save();

        return $this;
    }

    public function getTask()
    {
        return $this->task;
    }

    private function save(): void
    {
        $this->entityManager->persist($this->task);
        $this->entityManager->flush();
    }
}
