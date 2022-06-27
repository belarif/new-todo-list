<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TaskRepository;

class TaskService
{
    private ManagerRegistry $managerRegistry;

    private TaskRepository $taskRepository;

    public function __construct(ManagerRegistry $managerRegistry, TaskRepository $taskRepository)
    {
        $this->managerRegistry = $managerRegistry;
        $this->taskRepository = $taskRepository;
    }

    public function tasksList()
    {
        return $this->managerRegistry->getManager()->getRepository(Task::class)->findAll();
    }

    public function taskCreate($task)
    {
        $this->taskRepository->add($task, true);
    }
}