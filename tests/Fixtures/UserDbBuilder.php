<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDbBuilder
{
    private User $user;
    private ContainerInterface $container;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(User $user, UserRepository $userRepository, ContainerInterface $container)
    {
        $this->user = $user;
        $this->container = $container;
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->userRepository = $userRepository;
    }

    public function setUsername(string $username): self
    {
        $this->user->setUsername($username);
        $this->save();

        return $this;
    }

    public function setPassword(string $password): self
    {
        $hasher = $this->container->get(UserPasswordHasherInterface::class);
        $this->user->setPassword($hasher->hashPassword($this->user, $password));
        $this->save();

        return $this;
    }

    public function addRole(Role $role, Role ...$otherRoles): self
    {
        array_map(function (Role $role) {
            $this->user->addRole($role);
        }, array_merge([$role], $otherRoles));

        $this->save();

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    private function save(): void
    {
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }
}
