<?php

namespace App\Repository;

use App\Entity\Action;
use App\Entity\Culture;
use App\Entity\Entite;
use App\Entity\Historique;
use App\Entity\Recolte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class HistoriqueRepository
 *
 * @package App\Repository
 * @method Historique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historique[]    findAll()
 * @method Historique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueRepository extends ServiceEntityRepository
{
    /**
     * HistoriqueRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historique::class);
    }

    /**
     * @return mixed
     */
    public function findByAction()
    {
        return $this->createQueryBuilder('h')
            ->join(Entite::class, 'e', 'WITH', 'h.entite = e')
            ->andWhere("e.libelle = '".Action::class."'")
            ->orderBy('h.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return mixed
     */
    public function findByCulture()
    {
        return $this->createQueryBuilder('h')
            ->join(Entite::class, 'e', 'WITH', 'h.entite = e')
            ->andWhere("e.libelle = '".Culture::class."'")
            ->orderBy('h.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return mixed
     */
    public function findByEntite()
    {
        return $this->createQueryBuilder('h')
            ->join(Entite::class, 'e', 'WITH', 'h.entite = e')
            ->andWhere("e.libelle = '".Entite::class."'")
            ->orderBy('h.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return mixed
     */
    public function findByRecolte()
    {
        return $this->createQueryBuilder('h')
            ->join(Entite::class, 'e', 'WITH', 'h.entite = e')
            ->andWhere("e.libelle = '".Recolte::class."'")
            ->orderBy('h.id', 'DESC')
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
