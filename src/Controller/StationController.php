<?php

namespace App\Controller;

use App\Repository\StationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController] #[Route('/api/station')]
class StationController extends AbstractBaseController
{
    #[Route('/list/region', name: 'station.region_list', methods: ['GET'])]
    public function getListRegion(StationRepository $stationRepository): JsonResponse
    {
        $data = $stationRepository->findAllRegion();

        return new JsonResponse(['data' => array_values(array_unique($data, SORT_REGULAR))]);
    }


}