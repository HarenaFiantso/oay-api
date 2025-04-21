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

#[AllowDynamicProperties]
#[Route('/api/user')]
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

    /**
     * Create or update a user.
     *
     * @Route("/add", name: "user.add", methods: ['POST', 'PUT'])
     */
    #[Route('/add', name: 'user.add', methods: ['POST', 'PUT'])]
    public function createOrUpdateUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->manager->handleUser($data);

        return $this->save($user)
            ? new JsonResponse(['message' => 'success'], Response::HTTP_OK)
            : new JsonResponse(['message' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Get the list of users.
     *
     * @Route("/", name: "user.getList", methods: ['GET'])
     */
    #[Route('/', name: 'user.getList', methods: ['GET'])]
    public function getUserList(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $userList = array_map(fn($user) => json_decode($this->serializer->serialize($user)), $users);

        return new JsonResponse(['list' => $userList], Response::HTTP_OK);
    }

    /**
     * Get details of a specific user.
     *
     * @Route("/details/{id}", name: "user.getDetails", methods: ['GET'])
     */
    #[Route('/{id}', name: 'user.getDetails', methods: ['GET'])]
    public function getUserDetails(User $user): JsonResponse
    {
        $thisUser = [
            'id' => $user->getId(),
            'avatar' => $user->getAvatarUrl(),
            'name' => $user->getFullName(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'gender' => $user->getGender() ?? 'Just a human bro',
        ];

        return new JsonResponse(['user' => $thisUser], Response::HTTP_OK);
    }

    /**
     * Remove a user.
     *
     * @Route("/delete/{id}", name: "user.delete", methods: ['DELETE'])
     */
    #[Route('/delete/{id}', name: 'user.getProfile', methods: ['DELETE'])]
    public function removeUser(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        return $this->delete($user)
            ? new JsonResponse(['status' => 'success'], Response::HTTP_OK)
            : new JsonResponse(['status' => 'error'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Search a user.
     *
     * @Route("/search, name: "user.search", methods: ['DELETE'])
     */
    #[Route('/search', name: 'user.search', methods: ['GET'])]
    public function findUser(UserRepository $userRepository, Request $request): JsonResponse
    {
        $term = $request->get('term');
        try {
            $data = $userRepository->searchUser($term);

            return new JsonResponse(['status' => 'success', 'data' => $data]);
        } catch (\Exception) {
            return new JsonResponse(['status' => 'error']);
        }
    }

    /**
     * Get notifications.
     *
     * @Route("/notifications/{id}, name: "user.getNotifications", methods: ['GET'])
     */
    #[Route('/notifications/{id}', name: 'user.getNotifications', methods: ['GET'])]
    public function getNotifications(User $user, NotificationRepository $notificationRepository): JsonResponse
    {
        $notifs = $notificationRepository->findByUser($user);
        $data = [];

        foreach ($notifs as $key => $notification) {
            $data[$key]['title'] = $notification->getTitle();
            $data[$key]['id'] = $notification->getId();
            $data[$key]['dateAdd'] = $notification->getCreatedAt()->format('d-m-Y H:i');
        }

        return new JsonResponse(['notifs' => $data]);
    }

    public function getCountNotifs(User $user, NotificationRepository $notificationRepository): JsonResponse
    {
        $notifs = count($notificationRepository->findByUser($user));

        return new JsonResponse(['notifs' => $notifs]);
    }
}
