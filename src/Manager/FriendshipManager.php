<?php

namespace App\Manager;

use App\Entity\Friendship;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FriendshipManager extends AbstractManager
{
    private EntityManagerInterface $manager;

    public function __construct(
        UserRepository              $userRepository,
        UserPasswordHasherInterface $userPasswordEncoder,
        EntityManagerInterface      $entityManager)
    {
        parent::__construct($userRepository, $userPasswordEncoder);
        $this->manager = $entityManager;
    }

    public function handleFriendsRequest(Request $request)
    {
        $user = $this->userRepository->find($request->get('user'));
        $userSent = $this->userRepository->find($request->get('userSent'));
        $friend = new Friendship();
        $friend->setRequestingUser($user);
        $friend->setIsAccepted(false);

        $this->manager->persist($friend);
        $userSent->addFriend($friend);

        return $userSent;
    }
}