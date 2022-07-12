<?php declare(strict_types=1);

namespace App\Tests\Regressions;

use App\Entity\User;
use App\Tests\TodoListFunctionalTestCase;

final class TDL010Test extends TodoListFunctionalTestCase {
	public function test_it_should_create_user(): void {
		$client = $this->createTodoListClientWithLoggedUser();
		$user = $client->getCurrentLoggedUser();

		self::assertNotNull($user);
	}

	public function test_it_should_fetch_all_user(): void {
		$client = $this->createTodoListClientWithLoggedUser();
		$fixtures = $client->createFixtureBuilder();
		$logged_user = $client->getCurrentLoggedUser();

		$user_1 = $fixtures->user()
			->createUser(User::fromFixture())
			->setUsername('user_1')
			->getUser();

		$user_2 = $fixtures->user()
			->createUser(User::fromFixture())
			->setUsername('user_2')
			->getUser();

		$user_3 = $fixtures->user()
			->createUser(User::fromFixture())
			->setUsername('user_3')
			->getUser();


		$response = $client->sendRequest('GET', '/users');
		$crawler = $client->getCrawler();

		self::assertTrue($response->isOk());
		self::assertSame('Liste des utilisateurs', $crawler->filter('h1')->first()->text());
		self::assertCount(4, $crawler->filter('tbody tr'));
	}
}
