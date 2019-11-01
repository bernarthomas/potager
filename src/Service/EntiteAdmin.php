<?php
namespace App\Service;

use App\Entity\Action;
use App\Entity\Entite;
use App\Entity\Recolte;
use App\Interfaces\EntiteInterface;
use App\Interfaces\Historisable;
use App\Repository\ActionRepository;
use App\Repository\EntiteRepository;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class EntiteAdmin
 * @package App\Service
 */
class EntiteAdmin extends ServiceParent
{

    /** @var ActionRepository  */
    private $repositoryAction;

    /** @var EntiteRepository  */
    private $repositoryEntite;

    /** @var Historique  */
    private $sfServHistorique;


    /**
     * EntiteAdmin constructor.
     *
     * @param ActionRepository $repositoryAction
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     * @param EntiteRepository $repositoryEntite
     * @param HistoriqueRepository $repositoryHistorique
     * @param Historique $sfServHistorique
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(/*ActionAdmin $sfServActionAdmin, */ActionRepository $repositoryAction, ContainerInterface $container, EntityManagerInterface $entityManager, EntiteRepository $repositoryEntite, HistoriqueRepository $repositoryHistorique, Historique $sfServHistorique,  TokenStorageInterface $tokenStorage)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->repositoryAction = $repositoryAction;
        $this->repositoryEntite = $repositoryEntite;
        $this->repositoryHistorique = $repositoryHistorique;
        $this->sfServHistorique = $sfServHistorique;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * cherche l'action "create"
     * si ne trouve pas l'action "create" demande la creation de l'action "create" et demande la création de l'historique de l'action "create"
     * cherche l'entite "libelleEntite"
     * si l'entite "libelleEntite" n'existe pas créé de l'entite "libelleEntite" et demande la creation de l'historique de l'entité "libelleEntite"
     *
     * @param EntiteInterface $entite
     */
    public function cree(Historisable $entite = null)
    {
        try{
            $this->entityManager->beginTransaction();
            $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
            if (empty($action)) {
                $sfServAction = $this->container->get(ActionAdmin::class);
                $sfServAction->cree();
            }
            if (empty($entite)) {
                $entiteAction = $this->repositoryEntite->findOneBy(['libelle' => Action::class]);
                $entiteEntite = $this->repositoryEntite->findOneBy(['libelle' => Entite::class]);
                $entiteRecolte = $this->repositoryEntite->findOneBy(['libelle' => Recolte::class]);
                if (empty($entiteEntite)) {
                    $entiteEntite = new Entite();
                    $entiteEntite->setLibelle(Entite::class);
                    $this->entityManager->persist($entiteEntite);
                    $this->sfServHistorique->cree($action, $entiteEntite, $entiteEntite);
                }
                if (empty($entiteAction)) {
                    $entiteAction = new Entite();
                    $entiteAction->setLibelle(Action::class);
                    $this->entityManager->persist($entiteAction);
                    $this->sfServHistorique->cree($action, $entiteEntite, $entiteAction);
                }
                if (empty($entiteRecolte)) {
                    $entiteRecolte = new Entite();
                    $entiteRecolte->setLibelle(Recolte::class);
                    $this->entityManager->persist($entiteRecolte);
                    $this->sfServHistorique->cree($action, $entiteEntite, $entiteRecolte);
                }
            } else {
                $this->entityManager->persist($entite);
                $this->entityManager->flush();
                $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
                $this->sfServHistorique->cree($action, $entite, $entite);
            }
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
        }
    }

    /**
     * @return Entite[]
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
        return $this->repositoryHistorique->findByEntite();
    }
}