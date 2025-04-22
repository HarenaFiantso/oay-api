<?php

namespace App\Controller;

use App\Manager\CanYouGiveMeARideManager;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController] #[Route('/api/can_you_give_me_a_ride')]
class CanYouGiveMeARideController extends AbstractBaseController
{
    private UserRepository $userRepository;
    private CanYouGiveMeARideManager $canYouGiveMeARideManager;

    public function __construct(
        EntityManagerInterface   $entityManager,
        SerializerUtils          $serializerUtils,
        UserRepository           $userRepository,
        CanYouGiveMeARideManager $canYouGiveMeARideManager)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->userRepository = $userRepository;
        $this->canYouGiveMeARideManager = $canYouGiveMeARideManager;
    }

    /**
     * @throws \Exception
     */
    #[Route('/manage', name: 'canYouGiveMeGiveMeARive.add', methods: ['POST', 'PUT'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $canYouGiveMeARide = $this->canYouGiveMeARideManager->handleData($data);

        if ($this->save($canYouGiveMeARide)) {
            return new JsonResponse(['message' => 'success']);
        }

        return new JsonResponse(['message' => 'error']);
    }


}