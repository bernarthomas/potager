<?php
namespace App\Service;

use App\Entity\Culture;
use App\Entity\Entite;
use App\Entity\Historique;
use App\Entity\Utilisateur;
use App\Repository\ActionRepository;
use App\Repository\CultureRepository;
use App\Repository\EntiteRepository;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use \Datetime;
use \Exception;

class CultureAdmin
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var ActionRepository  */
    private $repositoryAction;

    /** @var CultureRepository  */
    private $repositoryCulture;

    /** @var EntiteRepository  */
    private $repositoryEntite;

    /** @var HistoriqueRepository  */
    private $repositoryHistorique;

    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /**
     * CultureAdmin constructor.
     *
     * @param ActionRepository $repositoryAction
     * @param EntityManagerInterface $entityManager
     * @param CultureRepository $repositoryCulture
     * @param EntiteRepository $repositoryEntite
     * @param HistoriqueRepository $repositoryHistorique
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ActionRepository $repositoryAction, EntityManagerInterface $entityManager, CultureRepository $repositoryCulture, EntiteRepository $repositoryEntite, HistoriqueRepository $repositoryHistorique, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->repositoryAction = $repositoryAction;
        $this->repositoryCulture = $repositoryCulture;
        $this->repositoryEntite = $repositoryEntite;
        $this->repositoryHistorique = $repositoryHistorique;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Culture $culture
     */
    public function cree(Culture $culture)
    {
        try{
            $this->entityManager->beginTransaction();
            $maintenant = new DateTime();
            /** @var Utilisateur $utilisateur */
            $utilisateur = $this->tokenStorage->getToken()->getUser();
            $this->entityManager->persist($culture);
            $this->entityManager->flush();
            $historique = new Historique();
            $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
            $entite = $this->repositoryEntite->findOneBy(['libelle' => Culture::class]);
            if (empty($entite)) {
                $entite = new Entite();
                $entite->setLibelle(Entite::class);
                $this->entityManager->persist($entite);
                $historiqueEntite = new Historique();
                $this->entityManager->persist($historiqueEntite);
                $historiqueEntite
                    ->setAction($action)
                    ->setDate($maintenant)
                    ->setEntite($entite)
                    ->setOccurenceId($entite->getId())
                    ->setUtilisateur($utilisateur)
                    ->setValeursModifiees($this->entiteToArray($entite));
                $this->entityManager->persist($historiqueEntite);
            }
            $this->entityManager->persist($historique);
            $historique
                ->setAction($action)
                ->setDate($maintenant)
                ->setEntite($entite)
                ->setOccurenceId($culture->getId())
                ->setUtilisateur($utilisateur)
                ->setValeursModifiees($this->cultureToArray($culture));
            $this->entityManager->persist($historique);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
        }
    }

    /**
     * @return Culture[]
     */
    public function liste()
    {
        return $this->repositoryCulture->findBy([], ['libelle' => 'ASC']);
    }

    /**
     * @return mixed
     */
    public function historique()
    {
        return $this->repositoryHistorique->findByEntiteLibelle(Culture::class);
    }

    /**
     * @param Culture $culture
     *
     * @return array
     */
    public function cultureToArray(Culture $culture) : array
    {
        return ['id' => $culture->getId(), 'libelle' => $culture->getLibelle()];
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