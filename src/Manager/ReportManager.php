<?php

namespace App\Manager;

use App\Entity\Report;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\VotingRepository;
use App\Utils\HtmlToEmoji;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ReportManager extends AbstractManager
{
    public const VOTE_CORRECT = 'true';
    public const VOTE_INCORRECT = 'false';
    public const VOTE_HAHA = 'haha';
    private VotingRepository $votingRepository;
    private CommentManager $commentManager;

    public function __construct(
        UserRepository              $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        VotingRepository            $votingRepository,
        CommentManager              $commentManager
    )
    {
        parent::__construct($userRepository, $userPasswordHasher);
        $this->votingRepository = $votingRepository;
        $this->commentManager = $commentManager;
    }

    public function createReportFromRequest(Request $request): Report
    {
        $data = $request->request->all();

        $report = new Report();
        $report
            ->setAuthor($this->userRepository->find($data['userId'] ?? null))
            ->setLocation($data['location'] ?? '')
            ->setCategory($data['cause'] ?? '')
            ->setDescription($data['description'] ?? '');

        return $report;
    }

    public function formatReportData(array $reports, ?User $currentUser = null): array
    {
        $formatted = [];

        foreach ($reports as $key => $report) {
            $author = $report->getUser();
            $authorPoints = $author?->getPoints() ?? 0;
            $authorFullName = $author?->getFullName() ?? 'Oay';
            $authorGender = $author?->getGender() ?? 'People';
            $authorId = $author?->getId();

            $correctVotes = $this->getVoteCount($report, self::VOTE_CORRECT);
            $incorrectVotes = $this->getVoteCount($report, self::VOTE_INCORRECT);
            $hahaVotes = $this->getVoteCount($report, self::VOTE_HAHA);
            $userVote = $this->getUserVote($report, $currentUser);

            $formatted[$key] = [
                'id' => $report->getId(),
                'photoUrl' => $report->getPhotoUrl(),
                'comments' => $this->commentManager->formatCommentsForReport($report),
                'author' => [
                    'points' => $authorPoints,
                    'fullName' => $authorFullName,
                    'gender' => $authorGender,
                    'id' => $authorId,
                ],
                'description' => HtmlToEmoji::convertTextToEmoji($report->getDescription()),
                'category' => $report->getCategory(),
                'location' => $report->getLocation(),
                'createdAt' => $report->getCreatedAt()?->format('d-m-Y H:i') ?? 'Today',
                'votes' => [
                    'correct' => $correctVotes,
                    'incorrect' => $incorrectVotes,
                    'haha' => $hahaVotes,
                    'user' => $userVote,
                ],
                'report' => [
                    'isOk' => $correctVotes > $incorrectVotes,
                ],
            ];
        }

        return $formatted;
    }

    public function getUserVote(Report $report, ?User $user = null)
    {
        return $this->votingRepository->findByUserVote($report, $user);
    }

    public function getVoteCount(Report $report, string $voteType): int
    {
        return $this->votingRepository->findByReportVote($report, $voteType);
    }
}
