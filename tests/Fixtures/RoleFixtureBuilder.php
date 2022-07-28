<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class RoleFixtureBuilder
{
    private ContainerInterface $container;

    private RoleRepository $repository;

    private EntityManagerInterface $entityManager;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $this->repository = $this->entityManager->getRepository(Role::class);
    }

    public function createRole(Role $role): RoleDbBuilder
    {
        if ($role->getRoleName()) {
            $roleName = $this->repository->findOneBy(['roleName' => $role->getRoleName()]);

            if ($roleName) {
                return $this->loadFrom($roleName);
            }
        }

        $this->entityManager->persist($role);
        $this->entityManager->flush();

        return new RoleDbBuilder(
            $role,
            $this->repository,
            $this->entityManager,
            $this->container
        );
    }

    public function loadFrom(string $roleName): RoleDbBuilder
    {
        $role = $this->repository->findOneBy(['roleName' => $roleName]);

        return new RoleDbBuilder(
            $role,
            $this->repository,
            $this->container
        );
    }
}
