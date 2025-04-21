<?php

namespace App\Manager;

use App\Entity\Offer;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OfferManager extends AbstractManager
{
    public function __construct(
        UserRepository                   $userRepository,
        UserPasswordHasherInterface      $userPasswordHasher,
    )
    {
        parent::__construct($userRepository, $userPasswordHasher);
    }

    /**
     * Handles the creation of a new Offer entity from given data.
     *
     * @param array $data The data array containing offer details.
     * @return Offer The populated Offer entity.
     * @throws Exception When required data is invalid or missing.
     */
    public function handleOffer(array $data): Offer
    {
        $user = $this->userRepository->find($data['userId'] ?? null);
        if (!$user) {
            throw new Exception('Invalid user ID provided.');
        }

        $departureAt = isset($data['departureAt'])
            ? new \DateTimeImmutable($data['departureAt'])
            : new \DateTimeImmutable('now');

        return (new Offer())
            ->setCreator($user)
            ->setDepartureLocation($data['departure'] ?? '')
            ->setArrivalLocation($data['destination'] ?? '')
            ->setNumberOfSeats((int)($data['numberOfSeats'] ?? 0))
            ->setContactInfo($data['contact'] ?? '')
            ->setPrice($data['price'] ?? null)
            ->setIsAvailable(true)
            ->setDepartureAt($departureAt);
    }
}
