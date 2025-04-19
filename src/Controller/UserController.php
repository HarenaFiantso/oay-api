<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/user')]
final class UserController extends AbstractBaseController
{
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils)
    {
        parent::__construct($entityManager, $serializerUtils);
    }

    #[Route('/list', name: 'user.getListUser')]
    public function getListUser(UserRepository $userRepository): JsonResponse
    {
        $data = $userRepository->findAll();
        $lists = [];

        foreach ($data as $item) {
            $lists[] = json_decode($this->serializer->serialize($item));
        }

        return new JsonResponse(['list' => $lists]);
    }
}
