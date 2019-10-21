<?php

namespace App\Repository;

use App\Entity\Entite;
use App\Entity\Historique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class HistoriqueRepository
 * @package App\Repository
 * @method Historique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historique[]    findAll()
 * @method Historique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historique::class);
    }

    /**
     * @param $libelleEntite
     *
     * @return mixed
     */
    public function findByEntiteLibelle($libelleEntite)
    {
        return $this->createQueryBuilder('h')
            ->join(Entite::class, 'e', 'WITH', 'h.entite = e')
            ->andWhere('e.libelle = :libelle')
            ->setParameter('libelle', $libelleEntite)
            ->orderBy('h.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return mixed
     */
    public function findByEntiteLibelleLike()
    {
        return $this->createQueryBuilder('h')
            ->andWhere("h.valeursModifiees LIKE '%App\\\Entity%'")
            ->orderBy('h.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
}
