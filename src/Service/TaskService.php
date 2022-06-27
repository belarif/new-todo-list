<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TaskRepository;

class TaskService
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function tasksList()
    {
        return $this->managerRegistry->getManager()->getRepository(Task::class)->findAll();
    }
}