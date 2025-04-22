<?php

namespace App\Controller;

use App\Manager\CanYouGiveMeARideManager;
use App\Repository\CanYouGiveMeARideRepository;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController] #[Route('/api/can_you_give_me_a_ride')]
final class CanYouGiveMeARideController extends AbstractBaseController
{
    private UserRepository $userRepository;
    private CanYouGiveMeARideManager $canYouGiveMeARideManager;

    public function __construct(
        EntityManagerInterface   $entityManager,
        SerializerUtils          $serializerUtils,
        UserRepository           $userRepository,
        CanYouGiveMeARideManager $canYouGiveMeARideManager)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->userRepository = $userRepository;
        $this->canYouGiveMeARideManager = $canYouGiveMeARideManager;
    }

    /**
     * @throws \Exception
     */
    #[Route('/manage', name: 'canYouGiveMeGiveMeARive.add', methods: ['POST', 'PUT'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $canYouGiveMeARide = $this->canYouGiveMeARideManager->handleData($data);

        if ($this->save($canYouGiveMeARide)) {
            return new JsonResponse(['message' => 'success']);
        }

        return new JsonResponse(['message' => 'error']);
    }

    #[Route('/list', name: 'canYouGiveMeGiveMeARive.list', methods: ['GET', 'HEAD'])]
    public function listData(CanYouGiveMeARideRepository $canYouGiveMeARideRepository, Request $request): JsonResponse
    {
        $limit = $request->get('limit');
        $page = $request->get('page');
        $web = $request->get('web');

        $data = $web ? $canYouGiveMeARideRepository->findPaginatedForWeb($limit + 10, $page ? $page : 0) : $canYouGiveMeARideRepository->findPaginated($limit + 10);
        $lists = [];

        foreach ($data as $key => $canYouGiveMeARide) {
            $lists[$key]['id'] = $canYouGiveMeARide->getId();
            $lists[$key]['departureLocation'] = $canYouGiveMeARide->getDepartureLocation();
            $lists[$key]['arrivalLocation'] = $canYouGiveMeARide->getArrivalLocation();
            $lists[$key]['creator']['fullName'] = $canYouGiveMeARide->getCreator() ? $canYouGiveMeARide->getCreator()->getFullName() : 'Me';
            $lists[$key]['departureDate'] = $canYouGiveMeARide->getDepartureDate() ? $canYouGiveMeARide->getDepartureDate()->format('d-m-Y H:i') : 'Today';
            $lists[$key]['contactInfo'] = $canYouGiveMeARide->getContactInfo();
            $lists[$key]['preferences'] = $canYouGiveMeARide->getPreferences();
            $lists[$key]['seatCount'] = $canYouGiveMeARide->getSeatCount();
            $lists[$key]['exactLocation'] = $canYouGiveMeARide->getExactLocation();
        }

        return new JsonResponse(['data' => $lists]);
    }
}