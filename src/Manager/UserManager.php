<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager extends AbstractManager
{
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct($userRepository, $userPasswordHasher);
    }

    public function handleUser(array $data): User
    {
        $user = $this->getUserFromData($data);

        $user->setEmail($data['email'] ?? 'notfound@gmail.com')
            ->setPassword($this->encoder->hashPassword($user, $data['password'] ?? '123456'))
            ->setRoles($this->getUserRoles($data['roles'] ?? []))
            ->setFullName($data['name'] ?? 'Simple user')
            ->setGender($data['gender'] ?? 'Man');

        return $user;
    }

    private function getUserFromData(array $data): User
    {
        if (!empty($data['id'])) {
            $user = $this->userRepository->find($data['id']);
            if ($user) {
                return $user;
            }
        }

        return new User();
    }

    private function getUserRoles(array $roles): array
    {
        if (empty($roles)) {
            return ['ROLE_USER'];
        }

        return array_map('strtoupper', $roles);
    }
}
