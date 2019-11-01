<?php
namespace App\Service;

use App\Entity\Recolte;
use App\Repository\ActionRepository;
use App\Repository\EntiteRepository;
use App\Repository\HistoriqueRepository;
use App\Repository\RecolteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RecolteAdmin
 * @package App\Service
 */
class RecolteAdmin extends ServiceParent
{
    /** @var array */
    private $recoltes;

    /** @var ActionRepository  */
    private $repositoryAction;

    /** @var EntiteRepository  */
    private $repositoryEntite;

    /** @var HistoriqueRepository  */
    private $repositoryHistorique;

    /** @var RecolteRepository  */
    private $repositoryRecolte;

    /** @var Historique  */
    private $sfServHistorique;

    /**
     * RecolteAdmin constructor.
     *
     * @param ActionRepository $repositoryAction
     * @param EntityManagerInterface $entityManager
     * @param EntiteRepository $repositoryEntite
     * @param HistoriqueRepository $repositoryHistorique
     * @param Historique $sfServHistorique
     * @param RecolteRepository $repositoryRecolte
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ActionRepository $repositoryAction, EntityManagerInterface $entityManager, EntiteRepository $repositoryEntite, HistoriqueRepository $repositoryHistorique, Historique $sfServHistorique, RecolteRepository $repositoryRecolte, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->repositoryAction = $repositoryAction;
        $this->repositoryEntite = $repositoryEntite;
        $this->repositoryHistorique = $repositoryHistorique;
        $this->repositoryRecolte = $repositoryRecolte;
        $this->sfServHistorique = $sfServHistorique;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * cherche l'action "create"
     * si ne trouve pas l'action "create" demande la creation de l'action "create" et demande la création de l'historique de l'action "create"
     * cherche l'entite "Recolte"
     * si l'entite "Recolte" n'existe pas demande la création de l'entite "Recolte" et demande la creation de l'historique de l'entité "Recolte"
     * crée la recolte passé en parametre par le formulaire et demande la creation de l'historique de la recolte pour cet id recolte et l'entite Recolte
     *
     * @param Recolte $recolte
     *
     * @throws \Exception
     */
    public function cree(Recolte $recolte)
    {
        try{
            $this->entityManager->beginTransaction();
            $this->entityManager->persist($recolte);
            $this->entityManager->flush();
            $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
            $entite = $this->repositoryEntite->findOneBy(['libelle' => Recolte::class]);
            $this->sfServHistorique->cree($action, $entite, $recolte);
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
        }
    }

    /**
     * @return array
     */
    public function decore()
    {
        $retour = [];
        $titres = [null];
        foreach($this->recoltes as $recolte) {
            $mois = $recolte->getMois();
            $culture = $recolte->getCulture();
            $titres[$mois] = $mois;
            $retour[$culture][$mois] = $recolte->getPoids();
        }
        $retour[0] = $titres;

        return $retour;
    }

    /**
     * @return mixed
     */
    public function historique()
    {
        return $this->repositoryHistorique->findByRecolte();
    }

    /**
     * @return Recolte[]
     */
    public function liste()
    {
        return $this->repositoryRecolte->findBy([], ['date' => 'ASC']);
    }

    /**
     * @param array $recoltes
     *
     * @return $this
     */
    public function setRecoltes(array $recoltes)
    {
        $this->recoltes = $recoltes;

        return $this;
    }
}