<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserException;
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

    /**
     * @throws UserException
     */
    public function userCreate(User $user)
    {
        if ($this->userRepository->findBy(['username' => $user->getUsername()])) {
            throw UserException::userExists($user);
        }

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
