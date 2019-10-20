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

class ActionAdmin
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
    public function cree(Action $action)
    {
        try{
            $this->entityManager->beginTransaction();
            $maintenant = new DateTime();
            /** @var Utilisateur $utilisateur */
            $utilisateur = $this->tokenStorage->getToken()->getUser();
            $this->entityManager->persist($action);
            $this->entityManager->flush();
            $entiteEntite = $this->repositoryEntite->findOneBy(['libelle' => Entite::class]);
            if (empty($entiteEntite)) {
                $entiteEntite = new Entite();
                $entiteEntite->setLibelle(Entite::class);
                $this->entityManager->persist($entiteEntite);
                $historiqueEntite = new Historique();
                $this->entityManager->persist($historiqueEntite);
                $historiqueEntite
                    ->setAction($action)
                    ->setDate($maintenant)
                    ->setEntite($entiteEntite)
                    ->setOccurenceId($entiteEntite->getId())
                    ->setUtilisateur($utilisateur)
                    ->setValeursModifiees($this->entiteToArray($entiteEntite));
                $this->entityManager->persist($historiqueEntite);
            }
            $entiteAction = $this->repositoryEntite->findOneBy(['libelle' => Action::class]);
            if (empty($entiteAction)) {
                $entiteAction = new Entite();
                $entiteAction->setLibelle(Action::class);
                $this->entityManager->persist($entiteAction);
            }
            $historiqueAction = new Historique();
            $this->entityManager->persist($historiqueAction);
            $historiqueAction
                ->setAction($action)
                ->setDate($maintenant)
                ->setEntite($entiteAction)
                ->setOccurenceId($action->getId())
                ->setUtilisateur($utilisateur)
                ->setValeursModifiees($this->actionToArray($action))
            ;
            $this->entityManager->persist($historiqueAction);
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
        return $this->repositoryAction->findBy([], ['libelle' => 'ASC']);
    }

    /**
     * @return mixed
     */
    public function historique()
    {
        return $this->repositoryHistorique->findByEntiteLibelle(Action::class);
    }

    /**
     * @param Action $action
     *
     * @return array
     */
    public function actionToArray(Action $action) : array
    {
        return ['id' => $action->getId(), 'libelle' => $action->getLibelle()];
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