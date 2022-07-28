<?php

namespace App\Entity\Builder;

use App\Entity\Role;

final class RoleBuilder
{
    private Role $role;

    public function __construct()
    {
        $this->role = new Role();
    }

    public function setRoleName(string $roleName): self
    {
        $this->role->setRoleName($roleName);

        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public static function newRole(): self
    {
        return new self();
    }
}
