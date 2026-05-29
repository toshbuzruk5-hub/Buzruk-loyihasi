<?php

declare(strict_types=1);

namespace App\Component\User;

final class UserInfoDto
{
    public function __construct(
        private ?int $id,
        private string $email,
        private array $roles,
        private string $fullname,
        private string $surname,
        private int $age,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getAge(): int
    {
        return $this->age;
    }
}