<?php

declare(strict_types=1);

namespace App\Component\User;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFactory
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(
        string $email,
        string $password,
        string $fullname,
        string $surname,
        int $age,
        array $roles = ['ROLE_USER']
    ): User {
        $user = new User();

        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setFullname($fullname);
        $user->setSurname($surname);
        $user->setAge($age);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        return $user;
    }

    public function makeInfoDto(User $user): UserInfoDto
    {
        return new UserInfoDto(
            $user->getId(),
            $user->getEmail() ?? '',
            $user->getRoles(),
            $user->getFullname() ?? '',
            $user->getSurname() ?? '',
            $user->getAge() ?? 0,
        );
    }
}