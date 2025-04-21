<?php

namespace App\Manager;

use App\Entity\Voting;
use App\Repository\UserRepository;
use App\Repository\VotingRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class VoteManager extends AbstractManager
{
    public const ALLOWED_VOTE_TYPES = ['correct', 'incorrect', 'haha'];

    public function __construct(
        UserRepository              $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
    )
    {
        parent::__construct($userRepository, $userPasswordHasher);
    }

    public function createVote(array $data): ?Voting
    {
        $voteType = $data['vote'] ?? null;
        $userId = $data['user'] ?? null;

        if (!in_array($voteType, self::ALLOWED_VOTE_TYPES, true) || !$userId) {
            return null;
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            return null;
        }

        $vote = new Voting();
        $vote
            ->setType($voteType)
            ->setUser($user);

        return $vote;
    }
}
