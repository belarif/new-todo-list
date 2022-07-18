<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\DependencyInjection\Container;

final class TaskFixtureBuilder
{
    private Container $container;
    private TaskRepository $repository;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createTask(Task $task): TaskDbBuilder
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $this->repository = $entityManager->getRepository(Task::class);

        $entityManager->persist($task);
        $entityManager->flush();

        return new TaskDbBuilder(
            $task,
            $this->repository,
            $this->container
        );
    }

    public function loadFrom(string $title): TaskDbBuilder
    {
        $task = $this->repository->findOneBy(['title' => $title]);

        return new TaskDbBuilder(
            $task,
            $this->repository,
            $this->container
        );
    }
}
