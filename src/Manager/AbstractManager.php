<?php

namespace App\Manager;

use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractManager
{
    protected UserRepository $userRepository;
    protected UserPasswordHasherInterface $encoder;

    public function __construct(
        UserRepository              $userRepository,
        UserPasswordHasherInterface $userPasswordEncoder
    )
    {
        $this->userRepository = $userRepository;
        $this->encoder = $userPasswordEncoder;
    }
}
