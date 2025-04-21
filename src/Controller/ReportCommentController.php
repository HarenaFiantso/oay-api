<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Report;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/report/comment')]
class ReportCommentController extends AbstractBaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerUtils        $serializerUtils,
        private UserRepository $userRepository,
    )
    {
        parent::__construct($entityManager, $serializerUtils);
    }

    #[Route('/add/{id}', name: 'report.comment.add', methods: ['POST', 'PUT'])]
    public function addCommentToReport(Request $request, Report $report): JsonResponse
    {
        $content = $request->get('content');
        $userId = $request->get('user');

        if (empty($content) || empty($userId)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Missing comment content or user ID'], 400);
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $comment = (new Comment())
            ->setContent($content)
            ->setAuthor($user);

        $report->addComment($comment);

        return $this->save($report)
            ? new JsonResponse(['status' => 'success'])
            : new JsonResponse(['status' => 'error'], 500);
    }

    #[Route('/remove/{id}', name: 'report.comment.delete', methods: ['DELETE'])]
    public function removeCommentFromReport(Request $request, CommentRepository $commentRepository, Report $report): JsonResponse
    {
        $commentId = $request->get('commentId');

        if (empty($commentId)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Missing comment ID'], 400);
        }

        $comment = $commentRepository->find($commentId);
        if (!$comment) {
            return new JsonResponse(['status' => 'error', 'message' => 'Comment not found'], 404);
        }

        $report->removeComment($comment);

        return $this->save($report)
            ? new JsonResponse(['status' => 'success'])
            : new JsonResponse(['status' => 'error'], 500);
    }

    #[Route('/reply/{id}', name: 'report.comment.reply', methods: ['POST', 'PUT'])]
    public function replyToComment(Request $request, Comment $parentComment): JsonResponse
    {
        $content = $request->get('reply');
        $userId = $request->get('user');

        if (empty($content) || empty($userId)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Missing reply content or user ID'], 400);
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $replyComment = (new Comment())
            ->setAuthor($user)
            ->setContent($content);

        $this->entityManager->persist($replyComment);
        $parentComment->addComment($replyComment);

        return $this->save($parentComment)
            ? new JsonResponse(['status' => 'success'])
            : new JsonResponse(['status' => 'error'], 500);
    }
}
