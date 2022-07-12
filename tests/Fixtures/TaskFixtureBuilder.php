<?php declare(strict_types=1);

namespace App\Tests\Fixtures;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class TaskFixtureBuilder {
	private ContainerInterface $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
}
