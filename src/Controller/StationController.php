<?php

namespace App\Controller;

use App\Repository\StationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/by-region', name: 'station.by_region', methods: ['GET'])]
    public function getListStation(StationRepository $stationRepository, Request $request): JsonResponse
    {
        $regions = json_decode($request->getContent(), true);
        $data = $stationRepository->findByRegion('Analamanga');
        if (isset($regions['region']) && $regions['region'] !== '' && $regions['region']) {
            $data = $stationRepository->findByRegion($regions['region']);
        }

        return new JsonResponse(['data' => $this->handleList($data)]);
    }

    public function handleList($stations = null): array
    {
        $list = [];
        foreach ($stations as $key => $station) {
            $list[$key]['name'] = $station->getName();
            $list[$key]['distributor'] = $station->getDistributor();
            $list[$key]['locality'] = $station->getLocality();
        }

        return array_values(array_unique($list, SORT_REGULAR));
    }
}