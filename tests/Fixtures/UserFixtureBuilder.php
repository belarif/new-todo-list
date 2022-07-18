<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtureBuilder
{
    private Container $container;
    private UserRepository $repository;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createUser(User $user): UserDbBuilder
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $hasher = $this->container->get(UserPasswordHasherInterface::class);
        $this->repository = $entityManager->getRepository(User::class);

        $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
        $entityManager->persist($user);
        $entityManager->flush();

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
}
