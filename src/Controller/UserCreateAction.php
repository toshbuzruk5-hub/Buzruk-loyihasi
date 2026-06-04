<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\User\UserFactory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use ApiPlatform\Symfony\Bundle\Attribute\AsController;

#[AsController]
final class UserCreateAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserFactory $userFactory,
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

        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse([
                'message' => 'email and password are required',
            ], 422);
        }
        $user =$this->userFactory->create(
            (string) $data['email'],
            (string) $data['password'],
            $data['roles'] ?? ['ROLE_USER']
        );

        if (isset($data['familyName'])) {
            $user->setFamilyName((string) $data['familyName']);
        }

        if (isset($data['isMarried'])) {
            $user->setIsMarried((bool) $data['isMarried']);
        }

        $this->em->persist($user);
        $this->em->flush();

        $userInfoDto = $this->userFactory->makeInfoDto($user);

        return new JsonResponse([
            'message' => 'User created successfully',
            'data' => [
                'id' => $userInfoDto->getId(),
                'email' => $userInfoDto->getEmail(),
                'roles' => $userInfoDto->getRoles(),
            ],
        ], 201);
    
    }
}
