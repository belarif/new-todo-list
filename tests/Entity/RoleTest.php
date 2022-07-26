<?php

namespace App\Tests\Entity;

use App\Entity\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testItShouldInitializeRoleWithEmptyId()
    {
        $role = new Role();

        self::assertEmpty($role->getId());
    }

    public function testItShouldUpdateRoleNameProperty()
    {
        $role = new Role();

        $role->setRoleName('ROLE_ADMIN');
        self::assertSame('ROLE_ADMIN', $role->getRoleName());
    }
}
