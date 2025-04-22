<?php

namespace App\Controller;

use App\Repository\StationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/api/station')]
final class StationController extends AbstractBaseController
{
    #[Route('/regions', name: 'station.regionList', methods: ['GET'])]
    public function listRegions(StationRepository $stationRepository): JsonResponse
    {
        $regions = $stationRepository->findAllRegions();

        return new JsonResponse([
            'data' => array_values(array_unique($regions, SORT_REGULAR))
        ]);
    }

    #[Route('/by-region', name: 'station.byRegion', methods: ['GET'])]
    public function listByRegion(StationRepository $stationRepository, Request $request): JsonResponse
    {
        $region = $request->query->get('region', 'Analamanga');
        $stations = $stationRepository->findStationsByRegion($region);

        return new JsonResponse([
            'data' => $this->normalizeStations($stations)
        ]);
    }

    #[Route('/search', name: 'station.search', methods: ['GET'])]
    public function searchStations(StationRepository $stationRepository, Request $request): JsonResponse
    {
        $search = $request->query->get('search');
        $region = $request->query->get('region', 'Analamanga');
        $limit = (int)$request->query->get('limit', 10);

        $stations = $stationRepository->searchStations($search, $region, $limit);

        return new JsonResponse([
            'data' => $this->normalizeStations($stations)
        ]);
    }

    private function normalizeStations(iterable $stations): array
    {
        $normalized = [];

        foreach ($stations as $station) {
            $normalized[] = [
                'name' => $station->getName(),
                'distributor' => $station->getDistributor(),
                'locality' => $station->getLocality(),
            ];
        }

        return array_values(array_unique($normalized, SORT_REGULAR));
    }
}
