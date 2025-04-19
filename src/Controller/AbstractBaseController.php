<?php

namespace App\Controller;

use App\CustomInterface\CustomManagerInterface;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AbstractBaseController extends AbstractController implements CustomManagerInterface
{
    protected EntityManagerInterface $entityManager;
    protected SerializerUtils $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerUtils        $serializerUtils
    )
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializerUtils;
    }

    public function save(object $entityObject): bool
    {
        try {
            if (method_exists($entityObject, 'getId') && !$entityObject->getId()) {
                $this->entityManager->persist($entityObject);
            }

            $this->entityManager->flush();

            return true;
        } catch (\Throwable $exception) {
            throw new HttpException(500, 'Error when saving entity.', $exception);
        }
    }

    public function update(object $entityObject): bool
    {
        try {
            $this->entityManager->flush();

            return true;
        } catch (\Throwable $exception) {
            throw new HttpException(500, 'Error when updating entity.', $exception);
        }
    }

    public function delete(object $entityObject): bool
    {
        try {
            $this->entityManager->remove($entityObject);
            $this->entityManager->flush();

            return true;
        } catch (\Throwable $exception) {
            throw new HttpException(500, 'Error when deleting entity.', $exception);
        }
    }

    public function getList(object $entityObject): array
    {
        throw new \LogicException('Not implemented.');
    }
}
