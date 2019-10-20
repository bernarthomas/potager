<?php

namespace App\Repository;

use App\Entity\Entite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class EntiteRepository
 * @package App\Repository
 * @method Entite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entite[]    findAll()
 * @method Entite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entite::class);
    }
}
