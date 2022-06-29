<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class TaskController extends AbstractController
{

    #[Route('/tasks', name: 'app_task_list', methods: ['GET'])]
    public function listAction(TaskService $taskService): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $taskService->tasksList()]);
    }

    #[Route('/tasks/create', name: 'app_task_create', methods: ['GET','POST'])]
    public function createAction(Request $request, TaskService $taskService)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->taskCreate($task);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'app_task_edit', methods: ['GET','POST'])]
    public function editAction(Task $task, Request $request, TaskService $taskService)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->taskEdit($task);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'app_task_toggle')]
    public function toggleTaskAction(Task $task, TaskService $taskService)
    {
        $task->toggle(!$task->isDone());
        $taskService->toggleTask($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'app_task_delete')]
    public function deleteTaskAction(Task $task, TaskService $taskService)
    {
        $taskService->deleteTask($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}

