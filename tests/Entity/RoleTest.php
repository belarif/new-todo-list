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
}
