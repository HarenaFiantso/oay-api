<?php

namespace App\Manager;

use App\Repository\TodoRepository;

class TodoManager
{
    private TodoRepository $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function findAll(): array
    {
        $todos = $this->todoRepository->findBy([], ['id' => 'desc']);

        $list = [];

        foreach ($todos as $key => $todo) {
            $list[$key]['title'] = $todo->getTitle();
            $list[$key]['description'] = $todo->getDescription();
            $list[$key]['isCompleted'] = $todo->getIsCompleted();
            $list[$key]['id'] = $todo->getId();
        }

        return $list;
    }
}