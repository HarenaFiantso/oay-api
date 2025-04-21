<?php

namespace App\Controller;

use App\Manager\OfferManager;
use App\Repository\OfferRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/offer')]
final class OfferController extends AbstractBaseController
{
    public function __construct(
        EntityManagerInterface           $entityManager,
        SerializerUtils                  $serializerUtils,
        private readonly OfferRepository $offerRepository,
        private readonly OfferManager    $offerManager,
    )
    {
        parent::__construct($entityManager, $serializerUtils);
    }

    /**
     * @throws Exception
     */
    #[Route('/manage', name: 'offer.addNewOffer', methods: ['POST', 'PUT'])]
    public function addNewOffer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid request payload.'], 400);
        }

        try {
            $offer = $this->offerManager->handleOffer($data);
            $this->save($offer);

            return new JsonResponse(['status' => 'success'], 201);
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    #[Route('/list', name: 'offer.getList', methods: ['GET'])]
    public function getListOffer(Request $request): JsonResponse
    {
        $limit = (int)$request->get('limit', 10);
        $offers = $this->offerRepository->findPaginated($limit);

        $data = array_map(fn($offer) => [
            'id' => $offer->getId(),
            'creator' => $offer->getCreator()?->getFullName() ?? 'N/A',
            'departureLocation' => $offer->getDepartureLocation(),
            'arrivalLocation' => $offer->getArrivalLocation(),
            'numberOfSeats' => $offer->getNumberOfSeats(),
            'price' => $offer->getPrice(),
            'contactInfo' => $offer->getContactInfo() ?? 'N/A',
            'departureAt' => $offer->getDepartureAt()?->format('d-m-Y H:i') ?? 'Today',
        ], $offers);

        return new JsonResponse([
            'status' => 200,
            'message' => 'Offers fetched successfully.',
            'data' => $data,
        ]);
    }
}
