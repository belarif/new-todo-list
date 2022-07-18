<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class TodoListFixtureBuilder
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function user(): UserFixtureBuilder
    {
        return new UserFixtureBuilder($this->container);
    }

    public function role(): RoleFixtureBuilder
    {
        return new RoleFixtureBuilder($this->container);
    }

    public function task(): TaskFixtureBuilder
    {
        return new TaskFixtureBuilder($this->container);
    }
}
