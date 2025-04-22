<?php

namespace App\Controller;

use App\Repository\UsefulNumberRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController] #[Route('/api/number')]
final class UsefulNumberController extends AbstractBaseController
{
    #[Route('/', name: 'usefulNumber.list', methods: ['GET'])]
    public function findListNumber(UsefulNumberRepository $usefulNumberRepository): JsonResponse
    {
        $data = $usefulNumberRepository->findBy([], ['category' => 'DESC']);

        return new JsonResponse(['data' => $this->handleData($data)]);
    }

    #[Route('/search', name: 'usefulNumber.search', methods: ['GET'])]
    public function search(UsefulNumberRepository $usefulNumberRepository, Request $request): JsonResponse
    {
        $search = json_decode($request->getContent(), true);
        $data = $usefulNumberRepository->search($search['search'] ?? '');

        return new JsonResponse(['data' => $this->handleData($data)]);
    }

    public function handleData($data): array
    {
        $list = [];
        foreach ($data as $key => $item) {
            $list[$key]['name'] = $item->getName();
            $list[$key]['category'] = $item->getCategory();
            $list[$key]['phoneNumber'] = $item->getPhoneNumber();
        }

        return $list;
    }
}