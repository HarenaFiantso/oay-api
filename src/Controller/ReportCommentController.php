<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Report;
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
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerUtils        $serializerUtils,
        UserRepository         $userRepository)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->userRepository = $userRepository;
    }

    #[Route('/add/{id}', name: 'report.comment.add', methods: ['POST', 'PUT'])]
    public function commentPost(Request $request, Report $report): JsonResponse
    {
        $comment = new Comment();
        $commentMessage = $request->get('comment');
        $user = $request->get('user');

        $comment->setParentComment($commentMessage);
        $comment->setAuthor($this->userRepository->find($user));

        $report->addComment($comment);

        if ($this->save($report)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }
}