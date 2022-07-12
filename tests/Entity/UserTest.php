<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testItShouldInitializeUserWithEmptyId()
    {
        $user = new User();

        self::assertEmpty($user->getId());
    }

    public function testItShouldUpdateUsernameProperty()
    {
        $user = new User();

        self::assertEmpty($user->getUsername());
        $user->setUsername('ocine');
        self::assertSame('ocine', $user->getUsername());
    }

    public function testItShouldUpdatePasswordProperty()
    {
        $user = new User();

        self::assertEmpty($user->getPassword());
        $user->setPassword('test');
        self::assertSame('test', $user->getPassword());
    }

    public function testItShouldUpdateEmailProperty()
    {
        $user = new User();

        self::assertEmpty($user->getEmail());
        $user->setEmail('example@gmail.com');
        self::assertSame('example@gmail.com', $user->getEmail());
    }
}
