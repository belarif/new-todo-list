<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtureBuilder
{
    private Container $container;
    private UserRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function createUser(User $user): UserDbBuilder
    {
        $hasher = $this->container->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new UserDbBuilder(
            $user,
            $this->repository,
            $this->container
        );
    }

    public function loadFrom(string $username): UserDbBuilder
    {
        $user = $this->repository->findOneBy(['username' => $username]);

        return new UserDbBuilder(
            $user,
            $this->repository,
            $this->container
        );
    }

    public function loadFromId(int $id): UserDbBuilder
    {
        $user = $this->repository->find($id);

        return new UserDbBuilder(
            $user,
            $this->repository,
            $this->container
        );
    }
}
