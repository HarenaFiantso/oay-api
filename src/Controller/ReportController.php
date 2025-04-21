<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Report;
use App\Entity\User;
use App\Entity\Voting;
use App\Manager\ReportManager;
use App\Manager\VoteManager;
use App\Repository\NeighborhoodRepository;
use App\Repository\ReportRepository;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use OneSignal\OneSignal;
use Psr\Log\LoggerInterface;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[AsController]
#[Route('/api/report')]
class ReportController extends AbstractBaseController
{
    private const CORRECT = 'correct';
    private const INCORRECT = 'incorrect';
    private const POINT = 1;

    public function __construct(
        protected EntityManagerInterface       $entityManager,
        private readonly SerializerUtils       $serializerUtils,
        private readonly ParameterBagInterface $parameterBag,
        private readonly UserRepository        $userRepository,
        private readonly ReportManager         $reportManager,
        private readonly VoteManager           $voteManager,
        private readonly ReportRepository      $reportRepository,
        private readonly FilesystemOperator    $defaultStorage,
        private readonly OneSignal             $oneSignal,
        private readonly LoggerInterface       $logger,
    )
    {
        parent::__construct($entityManager, $serializerUtils);
    }

    #[Route('/manage', name: 'report.add', methods: ['POST', 'PUT'])]
    public function addReport(Request $request): JsonResponse
    {
        try {
            $report = $this->reportManager->createReportFromRequest($request);
            $this->handleImageUploadIfExists($report, $request);

            $this->entityManager->persist($report);
            $this->entityManager->flush();

            $this->sendOneSignalNotification($report, $request->getSchemeAndHttpHost());

            return new JsonResponse(['status' => 'success', 'id' => $report->getId()], 201);
        } catch (\Exception $e) {
            $this->logger->error('Failed to add report: {message}', ['message' => $e->getMessage()]);
            return new JsonResponse(['status' => 'error', 'message' => 'Unable to create report'], 400);
        } catch (FilesystemException $e) {
            $this->logger->error('Failed to add report: {message}', ['message' => $e->getMessage()]);
            return new JsonResponse(['status' => 'error', 'message' => 'Unable to create report'], 400);
        }
    }

    #[Route('/list', name: 'report.getList', methods: ['GET'])]
    public function getReport(Request $request): JsonResponse
    {
        try {
            $limit = (int)$request->query->get('limit', 10);
            $page = (int)$request->query->get('page', 1);
            $isWeb = $request->query->getBoolean('web');
            $search = $request->query->get('search');
            $userId = $request->query->get('user');
            $user = $userId ? $this->userRepository->find($userId) : null;

            $data = $this->fetchReports($search, $limit, $page, $isWeb);
            $lists = $user ? $this->reportManager->formatReportData($data, $user) : $data;

            return new JsonResponse(['data' => json_decode($this->serializerUtils->serialize($lists))]);
        } catch (\Exception $e) {
            $this->logger->error('Failed to list reports: {message}', ['message' => $e->getMessage()]);
            return new JsonResponse(['status' => 'error', 'message' => 'Unable to fetch reports'], 400);
        }
    }

    #[Route('/delete/{id}/{user}', name: 'report.remove', methods: ['DELETE'])]
    public function removeReport(Report $report, ?User $user): JsonResponse
    {
        if ($report->getAuthor() !== $user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized or invalid report'], 403);
        }

        try {
            $this->entityManager->remove($report);
            $this->entityManager->flush();
            return new JsonResponse(['status' => 'success']);
        } catch (\Exception $e) {
            $this->logger->error('Failed to delete report: {message}', ['message' => $e->getMessage()]);
            return new JsonResponse(['status' => 'error', 'message' => 'Unable to delete report'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/vote/{id}', name: 'report.voteReport', methods: ['POST'])]
    public function addVoting(Request $request, Report $report): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $user = $this->userRepository->find($data['user']);

            if (!$user) {
                return new JsonResponse(['status' => 'error', 'message' => 'User not found'], 404);
            }

            $this->handleExistingVotes($report, $user);
            $vote = $this->voteManager->createVote($data);
            $report->addVote($vote);
            $this->updateUserPoints($report, $user, $vote);

            $this->entityManager->persist($report);
            $this->entityManager->flush();

            if ($user !== $report->getAuthor()) {
                $this->createNotification($user, $report, $vote);
            }

            return new JsonResponse(['status' => 'success']);
        } catch (\Exception $e) {
            $this->logger->error('Failed to add vote: {message}', ['message' => $e->getMessage()]);
            return new JsonResponse(['status' => 'error', 'message' => 'Unable to add vote'], 400);
        }
    }

    #[Route('/vote/remove/{id}', name: 'report.vote_remove', methods: ['DELETE'])]
    public function removeVoting(Request $request, Report $report): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $user = $this->userRepository->find($data['user']);

            if (!$user) {
                return new JsonResponse(['status' => 'error', 'message' => 'User not found'], 404);
            }

            $this->removeVoteIfExists($report, $user);

            $this->entityManager->flush();
            return new JsonResponse(['status' => 'success']);
        } catch (\Exception $e) {
            $this->logger->error('Failed to remove vote: {message}', ['message' => $e->getMessage()]);
            return new JsonResponse(['status' => 'error', 'message' => 'Unable to remove vote'], 400);
        }
    }

    #[Route('/neighborhood/find', name: 'report.findFokontany', methods: ['POST'])]
    public function getNeighborhood(Request $request, NeighborhoodRepository $neighborhoodRepository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $search = $data['search'] ?? '';
            $fokontanies = $neighborhoodRepository->findNeighborhood($search);

            $lists = array_map(fn($fokontany) => ['label' => $fokontany->getName(), 'value' => $fokontany->getName()], $fokontanies);
            return new JsonResponse(['data' => array_values(array_unique($lists, SORT_REGULAR))]);
        } catch (\Exception $e) {
            $this->logger->error('Failed to find fokontany: {message}', ['message' => $e->getMessage()]);
            return new JsonResponse(['status' => 'error', 'message' => 'Unable to fetch fokontany'], 400);
        }
    }

    /**
     * @throws FilesystemException
     */
    private function handleImageUploadIfExists(Report $report, Request $request): void
    {
        $file = $request->files->get('image');
        if ($file) {
            $newFilename = $this->handleImageUpload($file, $request->getSchemeAndHttpHost());
            $report->setPhotoUrl($newFilename);
        }
    }

    private function fetchReports(?string $search, int $limit, int $page, bool $isWeb): array
    {
        if ($search) {
            return $this->reportRepository->search($search, $limit + 10);
        }
        return $isWeb ? $this->reportRepository->findPaginated($limit + 10, $page) : $this->reportRepository->findAll($limit + 10);
    }

    private function removeVoteIfExists(Report $report, User $user): void
    {
        foreach ($report->getVotes() as $vote) {
            if ($vote->getUser() === $user) {
                $report->removeVote($vote);
                $this->entityManager->remove($vote);
            }
        }
    }

    /**
     * @throws FilesystemException
     */
    private function handleImageUpload($file, string $host): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = sprintf('%s-%s.%s', $safeFilename, Uuid::v4(), $file->guessExtension());

        $this->defaultStorage->writeStream('images/' . $newFilename, fopen($file->getPathname(), 'r'));

        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain->optimize($this->parameterBag->get('file_upload') . $newFilename);

        return sprintf('%s/image/%s', $host, $newFilename);
    }

    private function sendOneSignalNotification(Report $report, string $host): void
    {
        try {
            $this->oneSignal->notifications()->add([
                'contents' => ['en' => sprintf('%s - %s', $report->getLocation(), $report->getCategory())],
                'included_segments' => ['All'],
                'send_after' => new DateTimeImmutable(),
                'data' => ['Oay' => 'Share something'],
                'url' => $host,
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Failed to send OneSignal notification: {message}', ['message' => $e->getMessage()]);
        }
    }

    private function handleExistingVotes(Report $report, User $user): void
    {
        foreach ($report->getVotes() as $vote) {
            if ($vote->getUser() === $user) {
                $this->removeVoteAndAdjustPoints($report, $vote);
                $this->entityManager->remove($vote);
            }
        }
    }

    private function removeVoteAndAdjustPoints(Report $report, Voting $vote): void
    {
        $userPost = $report->getAuthor();
        $pointsChange = match ($vote->getType()) {
            self::CORRECT => -self::POINT,
            self::INCORRECT => self::POINT,
            default => 0,
        };

        $userPost->setPoints($userPost->getPoints() + $pointsChange);
    }

    private function updateUserPoints(Report $report, User $user, Voting $vote): void
    {
        if ($report->getAuthor() !== $user) {
            $userPost = $report->getAuthor();
            $pointsChange = match ($vote->getType()) {
                self::CORRECT => self::POINT,
                self::INCORRECT => -self::POINT,
                default => 0,
            };
            $userPost->setPoints($userPost->getPoints() + $pointsChange);
        }
    }

    private function createNotification(User $user, Report $report, Voting $vote): void
    {
        $notification = new Notification();
        $notification->setTitle(sprintf(
            'There is a change in your %s %s!',
            $user->getFullName(),
            $vote->getType()
        ));

        $report->getAuthor()->addNotification($notification);
        $this->entityManager->persist($notification);
    }
}
