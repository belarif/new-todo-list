<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class RoleFixtureBuilder
{
    private ContainerInterface $container;

    private RoleRepository $repository;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createRole(Role $role): RoleDbBuilder
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $this->repository = $entityManager->getRepository(Role::class);

        if ($role->getRoleName()) {
            $roleName = $this->repository->findOneBy(['roleName' => $role->getRoleName()]);

            if ($roleName) {
                return $this->loadFrom($roleName);
            }
        }

        $entityManager->persist($role);
        $entityManager->flush();

        return new RoleDbBuilder(
            $role,
            $this->repository,
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
