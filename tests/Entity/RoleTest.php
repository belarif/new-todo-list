<?php

namespace App\Tests\Entity;

use App\Entity\Builder\RoleBuilder;
use App\Entity\Role;
use PHPUnit\Framework\TestCase;
use Throwable;

class RoleTest extends TestCase
{
    public function testItShouldThrowExceptionWhenTryAccessToNotInitializePropertyId()
    {
        $role = RoleBuilder::newRole();

        self::expectException(Throwable::class);
        self::expectExceptionMessage(sprintf(
            'Typed property %s::$id must not be accessed before initialization',
            Role::class,
        ));

        $role->getRole()->getId();
    }

    public function testItShouldHydrateRoleNameProperty()
    {
        $role = RoleBuilder::newRole()
            ->setRoleName('ROLE_USER')
            ->getRole();

        self::assertNotNull($role->getRoleName());
    }
}
