<?php

namespace App\Controller;

use App\Entity\User;
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

    /**
     * @Route("/api/user/list")
     *
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     */
    #[Route('/', name: 'user.getList', methods: ['GET'])]
    public function getUserList(UserRepository $userRepository): JsonResponse
    {
        $data = $userRepository->findAll();
        $lists = [];

        foreach ($data as $item) {
            $lists[] = json_decode($this->serializer->serialize($item));
        }

        return new JsonResponse(['list' => $lists]);
    }

    /**
     * @Route("/api/user/details/{id}")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'user.getDetails', methods: ['GET'])]
    public function getUserDetails(User $user): JsonResponse
    {
        $thisUser['id'] = $user->getId();
        $thisUser['photo'] = $user->getAvatar();
        $thisUser['name'] = $user->getName();
        $thisUser['pseudo'] = $user->getPseudo();
        $thisUser['email'] = $user->getEmail();
        $thisUser['gender'] = $user->getGender() ?? 'Just a human bro';

        return new JsonResponse(['user' => $thisUser]);
    }

    /**
     * @Route("/api/user/delete/{id}")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'user.delete', methods: ['DELETE'])]
    public function removeUser(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->delete($user)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }
}
