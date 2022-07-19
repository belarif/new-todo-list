<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class RoleDbBuilder
{
    private Role $role;
    private ContainerInterface $container;
    private RoleRepository $roleRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Role $role,
        RoleRepository $roleRepository,
        EntityManagerInterface $entityManager,
        ContainerInterface $container
    ) {
        $this->role = $role;
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->roleRepository = $roleRepository;
    }

    public function setRoleName(string $roleName): self
    {
        $this->role->setRoleName($roleName);
        $this->save();

        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    private function save(): void
    {
        $this->entityManager->persist($this->role);
        $this->entityManager->flush();
    }
}
