<?php

namespace App\Controller;

use App\Manager\FriendshipManager;
use App\Repository\FriendshipRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/friendship')]
class FriendshipController extends AbstractBaseController
{
    public function __construct(
        EntityManagerInterface                $entityManager,
        SerializerUtils                       $serializerUtils,
        private readonly FriendshipManager    $friendshipManager,
        private readonly FriendshipRepository $friendshipRepository
    )
    {
        parent::__construct($entityManager, $serializerUtils);
    }

    #[Route('/request', name: 'friendship.request', methods: ['POST', 'PUT'])]
    public function sendFriendRequest(Request $request): JsonResponse
    {
        try {
            $userSent = $this->friendshipManager->handleFriendsRequest($request);

            return $this->save($userSent)
                ? new JsonResponse(['status' => 'success'])
                : new JsonResponse(['status' => 'error'], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/accepting', name: 'friendship.accepting', methods: ['POST', 'PUT'])]
    public function acceptFriend(Request $request): JsonResponse
    {
        try {
            $friend = $this->friendshipRepository->find($request->get('friendId'));

            if (!$friend) {
                return new JsonResponse(['status' => 'error', 'message' => 'Friendship not found'], 404);
            }

            $friend->setIsAccepted(true);
            $friend->setAcceptedAt(new \DateTimeImmutable());

            return $this->save($friend)
                ? new JsonResponse(['status' => 'success'])
                : new JsonResponse(['status' => 'error'], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/rejecting', name: 'friendship.rejecting', methods: ['POST', 'PUT'])]
    public function rejectFriend(Request $request): JsonResponse
    {
        try {
            $friend = $this->friendshipRepository->find($request->get('friendId'));

            if (!$friend) {
                return new JsonResponse(['status' => 'error', 'message' => 'Friendship not found'], 404);
            }

            $this->entityManager->remove($friend);
            $this->entityManager->flush();

            return new JsonResponse(['status' => 'success']);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
