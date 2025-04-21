<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/company')]
final class CompanyController extends AbstractBaseController
{
    public function __construct(
        EntityManagerInterface             $entityManager,
        SerializerUtils                    $serializerUtils,
        private readonly CompanyRepository $companyRepository
    )
    {
        parent::__construct($entityManager, $serializerUtils);
    }

    #[Route('/', name: 'company.getList', methods: ['GET'])]
    public function getCompanyList(Request $request): JsonResponse
    {
        try {
            $limit = (int)$request->query->get('limit', 10);
            $search = $request->query->get('search', '');

            $companies = $this->companyRepository->findByName($search, $limit);

            $data = array_map(fn(Company $company) => [
                'companyType' => $company->getCompanyType(),
                'name' => $company->getName(),
                'address' => $company->getAddress(),
                'responsiblePerson' => $company->getResponsiblePerson(),
            ], $companies);

            return new JsonResponse([
                'message' => 'success',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'message' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/add', name: 'company.add', methods: ['POST', 'PUT'])]
    public function addCompany(Request $request): JsonResponse
    {
        $company = (new Company())
            ->setCompanyType($request->get('companyType'))
            ->setName($request->get('name'))
            ->setAddress($request->get('address'))
            ->setContactInfo($request->get('contactInfo'))
            ->setResponsiblePerson($request->get('responsiblePerson'));

        return $this->save($company)
            ? new JsonResponse(['message' => 'success'])
            : new JsonResponse(['message' => 'error'], Response::HTTP_BAD_REQUEST);
    }
}
