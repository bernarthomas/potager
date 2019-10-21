<?php
namespace App\Service;

use App\Entity\Action;
use App\Entity\Entite;
use App\Entity\Historique;
use App\Entity\Utilisateur;
use App\Repository\ActionRepository;
use App\Repository\EntiteRepository;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use \Datetime;
use \Exception;

class EntiteAdmin
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var ActionRepository  */
    private $repositoryAction;

    /** @var EntiteRepository  */
    private $repositoryEntite;

    /** @var HistoriqueRepository  */
    private $repositoryHistorique;

    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /**
     * ActionAdmin constructor.
     *
     * @param ActionRepository $repositoryAction
     * @param EntityManagerInterface $entityManager
     * @param EntiteRepository $repositoryEntite
     * @param HistoriqueRepository $repositoryHistorique
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ActionRepository $repositoryAction, EntityManagerInterface $entityManager, EntiteRepository $repositoryEntite, HistoriqueRepository $repositoryHistorique, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->repositoryAction = $repositoryAction;
        $this->repositoryEntite = $repositoryEntite;
        $this->repositoryHistorique = $repositoryHistorique;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Action $action
     */
    public function cree(Entite $entite)
    {
        try{
            $this->entityManager->beginTransaction();
            $maintenant = new DateTime();
            /** @var Utilisateur $utilisateur */
            $utilisateur = $this->tokenStorage->getToken()->getUser();
            $this->entityManager->persist($entite);
            $this->entityManager->flush();
            $historiqueEntite = new Historique();
            $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
            $this->entityManager->persist($historiqueEntite);
            $historiqueEntite
                ->setAction($action)
                ->setDate($maintenant)
                ->setEntite($entite)
                ->setOccurenceId($entite->getId())
                ->setUtilisateur($utilisateur)
                ->setValeursModifiees($this->entiteToArray($entite));
            $this->entityManager->persist($historiqueEntite);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
        }
    }

    /**
     * @return Action[]
     */
    public function liste()
    {
        return $this->repositoryEntite->findBy([], ['libelle' => 'ASC']);
    }

    /**
     * @return mixed
     */
    public function historique()
    {
        return $this->repositoryHistorique->findByEntiteLibelle(Entite::class);
    }

    /**
     * @param Entite $entite
     *
     * @return array
     */
    public function entiteToArray(Entite $entite) : array
    {
        return ['id' => $entite->getId(), 'libelle' => $entite->getLibelle()];
    }
}