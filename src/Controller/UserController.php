<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\User;
use App\Manager\UserManager;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[AllowDynamicProperties] #[Route('/api/user')]
final class UserController extends AbstractBaseController
{
    private UserManager $manager;
    public function __construct(
        EntityManagerInterface      $entityManager,
        SerializerUtils             $serializerUtils,
        UserPasswordHasherInterface $encoder,
        UserManager                 $userManager
    )
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->encoder = $encoder;
        $this->manager = $userManager;
    }

    #[Route('/add', name: 'user.add', methods: ['POST', 'PUT'])]
    public function createOrUpdateUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->manager->handleUser($data);

        return $this->save($user)
            ? $this->json(['message' => 'success'], Response::HTTP_OK)
            : $this->json(['message' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    #[Route('/', name: 'user.getList', methods: ['GET'])]
    public function getUserList(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $userList = array_map(fn($user) => json_decode($this->serializer->serialize($user)), $users);

        return $this->json(['list' => $userList], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'user.getDetails', methods: ['GET'])]
    public function getUserDetails(User $user): JsonResponse
    {
        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'avatar' => $user->getAvatarUrl(),
                'name' => $user->getFullName(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'gender' => $user->getGender() ?? 'Just a human bro',
            ]
        ]);
    }

    #[Route('/delete/{id}', name: 'user.delete', methods: ['DELETE'])]
    public function removeUser(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        return $this->delete($user)
            ? $this->json(['status' => 'success'])
            : $this->json(['status' => 'error'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    #[Route('/search', name: 'user.search', methods: ['GET'])]
    public function findUser(UserRepository $userRepository, Request $request): JsonResponse
    {
        try {
            $term = $request->get('term');
            $data = $userRepository->searchUser($term);

            return $this->json(['status' => 'success', 'data' => $data]);
        } catch (\Throwable) {
            return $this->json(['status' => 'error']);
        }
    }

    #[Route('/notifications/{id}', name: 'user.getNotification', methods: ['GET'])]
    public function getNotification(User $user, NotificationRepository $notificationRepository): JsonResponse
    {
        $notifications = array_map(fn($n) => [
            'title' => $n->getTitle(),
            'id' => $n->getId(),
            'createdAt' => $n->getCreatedAt()->format('d-m-Y H:i'),
        ], $notificationRepository->findByUser($user));

        return $this->json(['notifications' => $notifications]);
    }

    #[Route('/notifications/count/{id}', name: 'user.countNewNotifications', methods: ['GET'])]
    public function getCountNotifications(User $user, NotificationRepository $notificationRepository): JsonResponse
    {
        $count = count($notificationRepository->findByUser($user));

        return $this->json(['notifications' => $count]);
    }

    #[Route('/notifications/all/{id}', name: 'user.getAllNotifications', methods: ['GET'])]
    public function getAllNotifications(Request $request, User $user, NotificationRepository $notificationRepository): JsonResponse
    {
        $limit = (int)$request->get('limit', 10);
        $page = (int)$request->get('page', 0);

        $notifications = array_map(fn($n) => [
            'title' => $n->getTitle(),
            'id' => $n->getId(),
            'createdAt' => $n->getDateAdd()->format('d-m-Y H:i'),
        ], $notificationRepository->findViewedNotif($user, $page, $limit));

        return $this->json(['notifications' => $notifications]);
    }

    #[Route('/viewAll/notifications/{id}', name: 'user.viewNotifications', methods: ['GET'])]
    public function viewNotifications(User $user): JsonResponse
    {
        foreach ($user->getNotifications() as $notification) {
            $notification->setIsView(true);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'success']);
    }

    #[Route('/friend/{id}', name: 'user.listUserFriends', methods: ['GET'])]
    public function userFriends(User $user): JsonResponse
    {
        try {
            $friends = array_map(fn($friend) => [
                'user' => $friend->getUser()->getName(),
                'dateFriend' => $friend->getDateAccepted()
                    ? $friend->getDateAccepted()->format('d-m-Y H:i')
                    : 'Your demand is pending',
            ], $user->getFriendships()->toArray());

            return $this->json(['status' => 'success', 'data' => $friends]);
        } catch (\Throwable) {
            return $this->json(['status' => 'error']);
        }
    }
}
