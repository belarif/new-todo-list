<?php

namespace App\Tests\Entity;

use App\Entity\Builder\RoleBuilder;
use App\Entity\Builder\UserBuilder;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Throwable;

class UserTest extends TestCase
{
    public function testItShouldThrowExceptionWhenTryAccessToNotInitializePropertyId()
    {
        $user = UserBuilder::newUser();

        self::expectException(Throwable::class);
        self::expectExceptionMessage(sprintf(
            'Typed property %s::$id must not be accessed before initialization',
            User::class,
        ));

        $user->getUer()->getId();
    }

    public function testItShouldHydrateUsernameProperty()
    {
        $user = UserBuilder::newUser()
            ->setUsername(uniqid())
            ->getUer();

        self::assertNotNull($user->getUsername());
    }

    public function testItShouldHydratePasswordProperty()
    {
        $user = UserBuilder::newUser()
            ->setPassword(uniqid())
            ->getUer();

        self::assertNotNull($user->getPassword());
    }

    public function testItShouldHydrateEmailProperty()
    {
        $user = UserBuilder::newUser()
            ->setEmail(uniqid())
            ->getUer();

        self::assertNotNull($user->getEmail());
    }

    public function testItShouldAssignRolesToUser()
    {
        $roles = [
            RoleBuilder::newRole()
                ->setRoleName('ROLE_USER')
                ->getRole(),
            RoleBuilder::newRole()
                ->setRoleName('ROLE_ADMIN')
                ->getRole(),
        ];

        $user = UserBuilder::newUser()
            ->setRoles($roles)
            ->getUer();

        self::assertNotNull($user->getRoles());
    }
}
