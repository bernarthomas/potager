<?php

namespace App\Repository;

use App\Entity\Recolte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class RecolteRepository
 * @package App\Repository
 * @method Recolte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recolte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recolte[]    findAll()
 * @method Recolte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecolteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recolte::class);
    }
}
