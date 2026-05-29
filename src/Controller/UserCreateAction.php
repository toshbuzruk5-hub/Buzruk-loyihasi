<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use ApiPlatform\Symfony\Bundle\Attribute\AsController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
final class UserCreateAction
{
    public function __construct(
        private readonly UserFactory $userFactory,
        private readonly UserManager $userManager,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse([
                'message' => 'Invalid JSON body',
            ], 400);
        }

        $requiredFields = ['email', 'password', 'fullname', 'surname', 'age'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return new JsonResponse([
                    'message' => sprintf('%s is required', $field),
                ], 422);
            }
        }

        if (!is_numeric($data['age']) || (int) $data['age'] < 0) {
            return new JsonResponse([
                'message' => 'age must be a non-negative number',
            ], 422);
        }

        $roles = isset($data['roles']) && is_array($data['roles'])
            ? $data['roles']
            : ['ROLE_USER'];

        $user = $this->userFactory->create(
            (string) $data['email'],
            (string) $data['password'],
            (string) $data['fullname'],
            (string) $data['surname'],
            (int) $data['age'],
            $roles
        );

        $this->userManager->save($user);

        $userInfoDto = $this->userFactory->makeInfoDto($user);

        return new JsonResponse([
            'message' => 'User created successfully',
            'data' => [
                'id' => $userInfoDto->getId(),
                'email' => $userInfoDto->getEmail(),
                'roles' => $userInfoDto->getRoles(),
                'fullname' => $userInfoDto->getFullname(),
                'surname' => $userInfoDto->getSurname(),
                'age' => $userInfoDto->getAge(),
            ],
        ], 201);
    }
}