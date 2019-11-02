<?php

namespace App\Repository;

use App\Entity\Recolte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * Class RecolteRepository
 * @package App\Repository
 *
 * @method Recolte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recolte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recolte[]    findAll()
 * @method Recolte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecolteRepository extends ServiceEntityRepository
{
    /**
     * RecolteRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recolte::class);
    }

    /**
     * Trouve la derniere date saisie
     *
     * @return bool|false|mixed
     */
    public function findDateLastId()
    {
        try {
            $connexion = $this->getEntityManager()->getConnection();
            $sql = 'SELECT date FROM recolte WHERE id = (SELECT MAX(id) FROM recolte)';
            $retour =  $connexion->executeQuery($sql)->fetchColumn();
        } catch (DBALException $e) {
            $retour = false;
        }

        return $retour;
    }
}