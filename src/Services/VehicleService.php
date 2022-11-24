<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleService
{
    private EntityManagerInterface $entityManager;

    private VehicleRepository $vehicleRepository;

    public function __construct(EntityManagerInterface $entityManager, VehicleRepository $vehicleRepository)
    {
        $this->entityManager = $entityManager;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    public function getVehiclesList(array $params): array
    {
        $qb = $this->vehicleRepository->getList($params);
        $paginator = new Paginator($qb, false);

        return [
            'count' => count($paginator),
            'list' => $qb->getQuery()->getArrayResult(),
        ];
    }
}
