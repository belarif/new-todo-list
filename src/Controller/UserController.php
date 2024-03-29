<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user_list', methods: ['GET'])]
    public function listAction(UserService $userService)
    {
        return $this->render('user/list.html.twig', ['users' => $userService->usersList()]);
    }

    #[Route('/users/create', name: 'app_user_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, UserService $userService, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(UserCreateType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            try {
                $userService->userCreate($user);
            } catch (Exception $e) {
                $this->addFlash('existing_user', $e->getMessage());

                return $this->redirectToRoute('app_user_create');
            }

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: 'app_user_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editAction(
        int $id,
        Request $request,
        UserService $userService,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ) {
        $user = $userRepository->getUser($id);

        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $userService->userEdit($user);

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    #[Route('/users/{id}/delete', name: 'app_user_delete', requirements: ['id' => '\d+'])]
    public function deleteAction(int $id, UserService $userService, UserRepository $userRepository)
    {
        $user = $userRepository->getUser($id);

        $userService->userDelete($user);

        $this->addFlash('success', "L'utilisateur a bien été supprimé.");

        return $this->redirectToRoute('app_user_list');
    }
}
