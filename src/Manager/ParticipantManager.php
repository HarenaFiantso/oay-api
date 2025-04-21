<?php

namespace App\Manager;

use App\Entity\Participant;
use App\Repository\ParticipantsRepository;
use Symfony\Component\HttpFoundation\Request;

class ParticipantManager
{
    private ParticipantsRepository $participantsRepository;

    public function __construct(ParticipantsRepository $participantsRepository)
    {
        $this->participantsRepository = $participantsRepository;
    }

    public function manageParticipants(Request $request)
    {
        $dataFromRequest = json_decode($request->getContent(), true);

        $participant = $this->participantsRepository->find($dataFromRequest['id']);
        $participant = $participant ?? new Participant();
        $participant->setName($dataFromRequest['firstName']);
        $participant->setLastName($dataFromRequest['lastname']);
        $participant->setGender($dataFromRequest['gender']);

        return $participant;
    }

    public function getParticipants(ParticipantsRepository $repository): array
    {
        $list = $repository->findBy([], ['id' => 'desc']);

        $lists = [];
        foreach ($list as $key => $participants) {
            $lists[$key]['id'] = $participants->getId();
            $lists[$key]['lastname'] = $participants->getLastName();
            $lists[$key]['firstName'] = $participants->getName();
            $lists[$key]['gender'] = $participants->getGender();
        }

        return $lists;
    }
}