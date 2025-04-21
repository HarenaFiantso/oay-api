<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Manager\ParticipantManager;
use App\Repository\ParticipantsRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/participants')]
final class ParticipantsController extends AbstractBaseController
{
    public function __construct(
        EntityManagerInterface              $entityManager,
        SerializerUtils                     $serializerUtils,
        private readonly ParticipantManager $participantManager,
    )
    {
        parent::__construct($entityManager, $serializerUtils);
    }

    #[Route('/list', name: 'participant.list', methods: ['GET'])]
    public function list(ParticipantsRepository $participantsRepository): JsonResponse
    {
        $participants = $this->participantManager->getParticipants($participantsRepository);

        return new JsonResponse([
            'status' => 'success',
            'data' => $participants,
        ]);
    }

    #[Route('/create', name: 'participant.create', methods: ['POST', 'PUT'])]
    public function create(Request $request): JsonResponse
    {
        $participant = $this->participantManager->manageParticipants($request);

        if ($this->save($participant)) {
            return new JsonResponse([
                'status' => 'success',
                'participantId' => $participant->getId(),
            ], 201);
        }

        return new JsonResponse(['status' => 'error'], 500);
    }

    #[Route('/{id}', name: 'participant.delete', methods: ['DELETE'])]
    public function removeParticipant(Participant $participant): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->delete($participant)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error'], 500);
    }
}
