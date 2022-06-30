<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserService
{
    private ManagerRegistry $managerRegistry;

    private UserRepository $userRepository;

    public function __construct(ManagerRegistry $managerRegistry, UserRepository $userRepository)
    {
        $this->managerRegistry = $managerRegistry;
        $this->userRepository = $userRepository;
    }

    public function usersList()
    {
        return $this->managerRegistry->getRepository(User::class)->findAll();
    }

    public function userCreate($user)
    {
        $this->userRepository->add($user, true);
    }

    public function userEdit($user)
    {
        $this->userRepository->add($user, true);
    }

    public function userDelete($user)
    {
        $this->userRepository->remove($user, true);
    }
}


