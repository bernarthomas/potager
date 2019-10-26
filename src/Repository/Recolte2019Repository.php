<?php

namespace App\Repository;

use App\Entity\Recolte2019;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recolte2019|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recolte2019|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recolte2019[]    findAll()
 * @method Recolte2019[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Recolte2019Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recolte2019::class);
    }
}
