<?php

namespace App\Manager;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TodoManager extends AbstractManager
{
    private TodoRepository $todoRepository;

    public function __construct(
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        TodoRepository              $repository)
    {
        parent::__construct(
            $userRepository,
            $passwordHasher,
        );
        $this->todoRepository = $repository;
    }

    public function findAll(): array
    {
        $todos = $this->todoRepository->findBy([], ['id' => 'DESC']);

        return array_map(fn(Todo $todo) => $this->normalizeTodo($todo), $todos);
    }

    public function createFromRequest(Request $request): Todo
    {
        $data = $this->parseRequestData($request);

        if (empty($data['title'])) {
            throw new BadRequestHttpException('Title is required');
        }

        $todo = new Todo();
        $this->updateTodoFields($todo, $data);

        return $todo;
    }

    public function updateFromRequest(Todo $todo, Request $request): Todo
    {
        $data = $this->parseRequestData($request);

        if (empty($data['title'])) {
            throw new BadRequestHttpException('Title is required');
        }

        $this->updateTodoFields($todo, $data);

        return $todo;
    }

    private function normalizeTodo(Todo $todo): array
    {
        return [
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
            'description' => $todo->getDescription() ?? '',
            'isCompleted' => $todo->isCompleted(),
        ];
    }

    private function parseRequestData(Request $request): array
    {
        $content = $request->getContent();

        if (empty($content)) {
            throw new BadRequestHttpException('Request body is empty');
        }

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        return $data;
    }

    private function updateTodoFields(Todo $todo, array $data): void
    {
        $todo->setTitle($data['title']);
        $todo->setDescription($data['description'] ?? null);
    }
}