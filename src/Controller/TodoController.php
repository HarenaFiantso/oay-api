<?php

namespace App\Controller;

use App\Manager\TodoManager;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController] #[Route('/api/todo')]
final class TodoController extends AbstractBaseController
{
    private TodoManager $todoManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerUtils        $serializerUtils,
        TodoManager            $manager)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->todoManager = $manager;
    }

    #[Route('/list', name: 'todo.list', methods: ['GET'])]
    public function getListTodo(): JsonResponse
    {
        $lists = $this->todoManager->findAll();

        return new JsonResponse(['todos' => $lists]);
    }
}