<?php
namespace App\Entity\Builder;

use App\Entity\Role;
use App\Entity\User;

final class UserBuilder {
	private User $user;

	public function __construct() {
		$this->user = new User();
	}

	public function setUsername(string $username): self {
		$this->user->setUsername($username);

		return $this;
	}

	public function setPassword(string $password): self {
		$this->user->setPassword($password);

		return $this;
	}

	public function setEmail(string $email): self {
		// Verifier que l'email est valid
		$this->user->setEmail($email);

		return $this;
	}

	public function addRole(Role $role): self {
		$this->user->addRole($role);

		return $this;
	}

	public function getUer(): User {
		return $this->user;
	}

	public static function newUser(): self {
		return new self();
	}
}
