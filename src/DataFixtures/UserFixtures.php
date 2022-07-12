<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_USER = 'ROLE_USER';

    private UserPasswordHasherInterface $passwordHasher;

    private RoleRepository $roleRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, RoleRepository $roleRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->roleRepository = $roleRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'username' => 'admin1',
                'email' => 'admin1@gmail.com',
                'password' => 'admin1',
                'role' => $this->roleRepository->findBy(['roleName' => RoleFixtures::ROLE_ADMIN]),
            ],
            [
                'username' => 'user1',
                'email' => 'user1@gmail.com',
                'password' => 'user1',
                'role' => $this->roleRepository->findBy(['roleName' => RoleFixtures::ROLE_USER]),
            ],
        ];

        foreach ($users as $newUser) {
            $user = new User();

            $user->setUsername($newUser['username']);
            $user->setEmail($newUser['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $newUser['password']));
            $user->setRoles($newUser['role']);

            $manager->persist($user);
            $manager->flush();
        }
    }
}
