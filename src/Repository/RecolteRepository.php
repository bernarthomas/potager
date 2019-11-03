<?php

namespace App\Repository;

use App\Entity\Culture;
use App\Entity\Recolte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

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

    /**
     * @return mixed
     */
    public function findCultureLast()
    {
        $entityManager = $this->getEntityManager();
        $sql = 'SELECT c.* FROM recolte r JOIN culture c ON c.id = r.culture_id WHERE r.id = (SELECT MAX(id) FROM recolte)';
        $resultSetMapping = new ResultSetMappingBuilder($entityManager);
        $resultSetMapping->addRootEntityFromClassMetadata(Culture::class, 'c');
        $query = $entityManager->createNativeQuery($sql, $resultSetMapping);

        return  $query->getOneOrNullResult();
    }
}