<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Manager\TodoManager;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/manage/todo', name: 'todo.create', methods: ['POST', 'PUT'])]
    public function manageTodo(Request $request): JsonResponse
    {
        $todo = $this->todoManager->manageTodo($request);
        if ($this->save($todo)) {
            return new JsonResponse(['status' => 'success', 'todo' => $todo->getId()]);
        }

        return new JsonResponse(['message' => 'error']);
    }

    #[Route('/done/todo/{id}', name: 'todo.done', methods: ['POST', 'PUT'])]
    public function finishedTodo(Todo $todo): JsonResponse
    {
        $todo->setIsCompleted(true);
        if ($this->save($todo)) {
            $lists = $this->todoManager->findAll();

            return new JsonResponse(['status' => 'success', 'todos' => $lists]);
        }

        return new JsonResponse(['status' => 'error']);
    }
}