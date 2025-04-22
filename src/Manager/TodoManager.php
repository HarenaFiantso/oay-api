<?php

namespace App\Manager;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Symfony\Component\HttpFoundation\Request;

readonly class TodoManager
{
    public function __construct(
        private TodoRepository $todoRepository
    )
    {
    }

    public function findAll(): array
    {
        $todos = $this->todoRepository->findBy([], ['id' => 'desc']);

        return array_map([$this, 'normalizeTodo'], $todos);
    }

    public function manageTodo(Request $request): Todo
    {
        $data = json_decode($request->getContent(), true);

        $todo = null;
        if (!empty($data['id'])) {
            $todo = $this->todoRepository->find($data['id']);
        }

        $todo ??= new Todo();

        if (!empty($data['title'])) {
            $todo->setTitle($data['title']);
        }

        if (array_key_exists('description', $data)) {
            $todo->setDescription($data['description']);
        }

        return $todo;
    }

    private function normalizeTodo(Todo $todo): array
    {
        return [
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
            'description' => $todo->getDescription(),
            'isCompleted' => $todo->getIsCompleted(),
        ];
    }
}
