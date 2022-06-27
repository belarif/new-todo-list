<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_it_should_initialize_user_with_empty_id()
    {
        $user = new User();

        self::assertEmpty($user->getId());
    }

    public function test_it_should_update_username_property()
    {
        $user = new User();

        self::assertEmpty($user->getUsername());
        $user->setUsername('ocine');
        self::assertSame('ocine', $user->getUsername());
    }

    public function test_it_should_update_password_property()
    {
        $user = new User();

        self::assertEmpty($user->getPassword());
        $user->setPassword('test');
        self::assertSame('test', $user->getPassword());
    }

    public function test_it_should_update_email_property()
    {
        $user = new User();

        self::assertEmpty($user->getEmail());
        $user->setEmail('example@gmail.com');
        self::assertSame('example@gmail.com', $user->getEmail());
    }
}