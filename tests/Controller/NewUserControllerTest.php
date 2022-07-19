<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\TodoListFunctionalTestCase;

final class NewUserControllerTest extends TodoListFunctionalTestCase
{
	public function test_it_should_redirect_user_on_login_page_when_try_to_accessing_on_create_user_page_without_login()
    {
        $client = $this->createTodoListClient(true);
        $response = $client->sendRequest('GET', '/users/create');

        self::assertTrue($response->isRedirect('http://localhost/login'));
    }

    public function test_it_should_access_on_create_user_page_when_user_is_logged()
    {
        $client = $this->createTodoListClientWithLoggedUser();
        $response = $client->sendRequest('GET', '/users/create');

        self::assertTrue($response->isOk());
    }

	public function test_it_should_create_user(): void {
		$client = $this->createTodoListClientWithLoggedUser();
		$fixtures = $client->createFixtureBuilder();

		$user = $fixtures->user()
			->loadFrom($username = uniqid('username-'))
			->getUser();
		self::assertNull($user);

		$response = $client->sendRequest(
			'POST',
			'/users/create',
			[
				'user' => [
					'username' => $username,
					'password' => [
						'first' => $password = uniqid('password'),
						'second' => $password,
					],
					'email' => $email = uniqid() . '@todolist.com',
				]
			]
		);
		self::assertTrue($response->isRedirect());
		self::assertNotNull(
			$newUser = $fixtures->user()
				->loadFrom($username)
				->getUser()
		);
		self::assertSame($email, $newUser->getEmail());
	}

    public function test_it_should_display_users_list_page()
    {
        $client = $this->createTodoListClientWithLoggedUser();
		$fixtures = $client->createFixtureBuilder();

		// loggedUser + 3 new users = 4
	    $fixtures->user()->createUser(User::fromFixture());
	    $fixtures->user()->createUser(User::fromFixture());
	    $fixtures->user()->createUser(User::fromFixture());

	    $response = $client->sendRequest('GET', '/users');
	    $crawler = $client->getCrawler();

	    self::assertTrue($response->isOk());
	    self::assertSame('Liste des utilisateurs', $crawler->filter('h1')->first()->text());
	    self::assertGreaterThan(4, $crawler->filter('tbody tr')->count());
    }

	public function test_it_should_display_user_edit_page()
	{
		$client = $this->createTodoListClientWithLoggedUser();
		$fixtures = $client->createFixtureBuilder();

		$user = $fixtures
			->user()
			->createUser(User::fromFixture())
			->getUser();

		self::assertNotEmpty($user->getId());

		$response = $client->sendRequest('GET', '/users/' . $user->getId() . '/edit');
		$crawler = $client->getCrawler();

		self::assertTrue($response->isOk());
		self::assertCount(1, $crawler->filter('form'));
		self::assertCount(1, $crawler->filter('input[id=user_username]'));
		self::assertCount(1, $crawler->filter('input[id=user_password_first]'));
		self::assertCount(1, $crawler->filter('input[id=user_password_second]'));
		self::assertCount(1, $crawler->filter('input[id=user_email]'));
		self::assertNotNull($crawler->selectButton('submit'));
	}

	public function test_it_should_create_update_user(): void {
		$client = $this->createTodoListClientWithLoggedUser();
		$fixtures = $client->createFixtureBuilder();

		$user = $fixtures->user()
			->createUser(User::fromFixture())
			->getUser();

		$newUsername = uniqid('new_username');
		$newEmail = 'email@gmail.com';
		self::assertNotSame($newUsername, $user->getUsername());
		self::assertNotSame($newEmail, $user->getUsername());

		$response = $client->sendRequest(
			'POST',
			'/users/' . $user->getId() . '/edit',
			[
				'user' => [
					'username' => $newUsername,
					'email' => $newEmail,
					'password' => [
						'first' => $user->getPassword(),
						'second' => $user->getPassword(),
					],
				]
			]
		);
		self::assertTrue($response->isRedirect());
		self::assertNotNull(
			$newUser = $fixtures->user()
				->loadFromId($user->getId())
				->getUser()
		);
		self::assertSame($newEmail, $newUser->getEmail());
		self::assertSame($newUsername, $newUser->getUsername());
	}
}
