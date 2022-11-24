<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
        $limit = $params['limit'] ?? 20;
        $offset = $params['offset'] ?? 0;

        return $this->createQueryBuilder('v')
            ->orWhere('v.vin LIKE :val')
            ->setParameter('val', "%{$search}%")
            ->orderBy("v.{$order}", $direction)
            ->setFirstResult($offset)
            ->setMaxResults($limit);
    }

}
