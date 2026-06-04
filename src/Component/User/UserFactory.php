<?php

declare(strict_types=1);

namespace App\Component\User;

use App\Component\User\UserInfoDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFactory
{
    public function __construct( 
        private readonly UserPasswordHasherInterface $passwordHasher
) {
}

    public function create(
        string $email,
        string $password,
        array $roles = ['ROLE_USER']
    ): User {
        $user = new User();

        $user->setEmail($email);
        $user->setRoles($roles);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        return $user;
    }

     public function makeInfoDto(User $user): UserInfoDto
    {
        return new UserInfoDto(
            $user->getId(),
            $user->getEmail(),
            $user->getRoles()
        );
    }
}