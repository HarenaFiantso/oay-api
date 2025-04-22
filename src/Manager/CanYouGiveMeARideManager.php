<?php

namespace App\Manager;

use App\Entity\CanYouGiveMeARide;
use App\Repository\CanYouGiveMeARideRepository;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CanYouGiveMeARideManager extends AbstractManager
{
    public function __construct(
        UserRepository              $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        CanYouGiveMeARideRepository $canYouGiveMeARideRepository)
    {
        parent::__construct($userRepository, $userPasswordHasher);
    }

    /**
     * @throws \Exception
     */
    public function handleData(array $data): CanYouGiveMeARide
    {
        $youGiveMeARide = new CanYouGiveMeARide();
        $youGiveMeARide
            ->setCreator($this->userRepository->find($data['userId'] ?? ''))
            ->setDepartureLocation($data['departureLocation'] ?? 'Tanà')
            ->setArrivalLocation($data['arrivalLocation'] ?? 'Tanà')
            ->setContactInfo($data['contactInfo'] ?? '')
            ->setExactLocation($data['exactLocation'] ?? null)
            ->setSeatCount($data['seatCount'] ?? 1)
            ->setPreferences($data['preference'] ?? 'Car')
            ->setDepartureDate(new \DateTimeImmutable($data['departureDate'] ?? 'now'));

        return $youGiveMeARide;
    }
}