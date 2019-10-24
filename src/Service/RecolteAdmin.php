<?php
namespace App\Service;

use App\Entity\Entite;
use App\Entity\Historique;
use App\Entity\Recolte;
use App\Entity\Utilisateur;
use App\Repository\ActionRepository;
use App\Repository\EntiteRepository;
use App\Repository\HistoriqueRepository;
use App\Repository\RecolteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use \Datetime;
use \Exception;

class RecolteAdmin
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var ActionRepository  */
    private $repositoryAction;

    /** @var EntiteRepository  */
    private $repositoryEntite;

    /** @var HistoriqueRepository  */
    private $repositoryHistorique;

    /** @var RecolteRepository  */
    private $repositoryRecolte;

    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /**
     * RecolteAdmin constructor.
     *
     * @param ActionRepository $repositoryAction
     * @param EntityManagerInterface $entityManager
     * @param EntiteRepository $repositoryEntite
     * @param HistoriqueRepository $repositoryHistorique
     * @param RecolteRepository $repositoryRecolte
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ActionRepository $repositoryAction, EntityManagerInterface $entityManager, EntiteRepository $repositoryEntite, HistoriqueRepository $repositoryHistorique, RecolteRepository $repositoryRecolte, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->repositoryAction = $repositoryAction;
        $this->repositoryEntite = $repositoryEntite;
        $this->repositoryHistorique = $repositoryHistorique;
        $this->repositoryRecolte = $repositoryRecolte;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Recolte $recolte
     */
    public function cree(Recolte $recolte)
    {
        try{
            $this->entityManager->beginTransaction();
            $maintenant = new DateTime();
            /** @var Utilisateur $utilisateur */
            $utilisateur = $this->tokenStorage->getToken()->getUser();
            $this->entityManager->persist($recolte);
            $this->entityManager->flush();
            $historique = new Historique();
            $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
            $entite = $this->repositoryEntite->findOneBy(['libelle' => Recolte::class]);
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
                ->setOccurenceId($recolte->getId())
                ->setUtilisateur($utilisateur)
                ->setValeursModifiees($this->recolteToArray($recolte));
            $this->entityManager->persist($historique);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
        }
    }

    /**
     * @return Recolte[]
     */
    public function liste()
    {
        return $this->repositoryRecolte->findBy([], ['date' => 'ASC']);
    }

    /**
     * @return mixed
     */
    public function historique()
    {
        return $this->repositoryHistorique->findByEntiteLibelle(Recolte::class);
    }

    /**
     * @param Recolte $recolte
     *
     * @return array
     */
    public function recolteToArray(Recolte $recolte) : array
    {
        $retour = [];
        $id = $recolte->getId();
        $commentaire = $recolte->getCommentaire();
        $date = $recolte->getDate();
        $poids = $recolte->getPoids();
        if (!empty($date)) {
            $dateFormatee = $date->format('Y-m-d h:i:s');
        }
        $culture = $recolte->getCulture();
        if (!empty($culture)) {
            $idCulture = $culture->getId();
            $libelleCulture = $culture->getLibelle();
        }
        if (!empty($id) && !empty($commentaire) && !empty($dateFormatee) && !empty($idCulture) && !empty($libelleCulture)  && !empty($poids)) {
            $retour = ['id' => $id, 'commentaire' => $commentaire,  'date' => $dateFormatee, 'id_culture' => $idCulture, 'libelle_culture' => $libelleCulture, 'poids' => $poids];
        }

        return $retour;
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