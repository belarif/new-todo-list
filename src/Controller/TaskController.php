<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks_done', name: 'app_task_list_done', methods: ['GET'])]
    public function listDoneAction(TaskService $taskService): Response
    {
        return $this->render('task/tasks_list_done.html.twig', ['tasks' => $taskService->tasksList(), 'user' => $this->getUser()]);
    }

    #[Route('/tasks_not_done', name: 'app_task_list_not_done', methods: ['GET'])]
    public function listNotDoneAction(TaskService $taskService): Response
    {
        return $this->render('task/tasks_list_not_done.html.twig', ['tasks' => $taskService->tasksList(), 'user' => $this->getUser()]);
    }

    #[Route('/tasks/create', name: 'app_task_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, TaskService $taskService)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $taskService->taskCreate($task);

            $this->addFlash('success', 'La tâche a bien été créée.');

            return $this->redirectToRoute('app_task_list_not_done');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'app_task_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editAction(int $id, Request $request, TaskService $taskService, TaskRepository $taskRepository)
    {
        $task = $taskRepository->getTask($id);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->taskEdit($task);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            if ($task->isDone()) {
                return $this->redirectToRoute('app_task_list_done');
            }

            return $this->redirectToRoute('app_task_list_not_done');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'app_task_toggle', requirements: ['id' => '\d+'])]
    public function toggleTaskAction(int $id, TaskService $taskService, TaskRepository $taskRepository)
    {
        $task = $taskRepository->getTask($id);

        $task->toggle(!$task->isDone());
        $taskService->toggleTask($task);

        if (!$task->isDone()) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle()));

            return $this->redirectToRoute('app_task_list_not_done');
        }

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('app_task_list_done');
    }

    #[Route('/tasks/{id}/delete', name: 'app_task_delete', requirements: ['id' => '\d+'])]
    public function deleteTaskAction(int $id, TaskService $taskService, TaskRepository $taskRepository)
    {
        $task = $taskRepository->getTask($id);

        $taskService->deleteTask($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        if (!$task->isDone()) {
            return $this->redirectToRoute('app_task_list_not_done');
        }

        return $this->redirectToRoute('app_task_list_done');
    }
}
