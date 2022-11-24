<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method QueryBuilder getList($params) Return query builder to get vehicle list for the given conditions
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    /**
     * @param mixed[] $params
     */
    public function getList(array $params): QueryBuilder
    {
        $search = $params['search'] ?? '';
        $order = $params['order'] ?? 'id';
        $direction = $params['direction'] ?? 'ASC';
        $limit = $params['limit'] ?? 25;
        $offset = $params['offset'] ?? 0;

        return $this->createQueryBuilder('v')
            ->orWhere('v.vin LIKE :val')
            ->setParameter('val', "%{$search}%")
            ->orderBy("v.{$order}", $direction)
            ->setFirstResult($offset)
            ->setMaxResults($limit);
    }

}
