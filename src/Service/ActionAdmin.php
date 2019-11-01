<?php
namespace App\Service;

use App\Entity\Action;
use App\Interfaces\EntiteInterface;
use App\Repository\ActionRepository;
use App\Repository\EntiteRepository;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use \Exception;

/**
 * Class ActionAdmin
 * @package App\Service
 */
class ActionAdmin extends ServiceParent
{
    /** @var ActionRepository  */
    private $repositoryAction;

    /** @var EntiteRepository  */
    private $repositoryEntite;

    /** @var HistoriqueRepository  */
    private $repositoryHistorique;

    /** @var EntiteAdmin  */
    private $sfServEntiteAdmin;

    /** @var Historique  */
    private $sfServHistorique;

    /**
     * ActionAdmin constructor.
     *
     * @param ActionRepository $repositoryAction
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     * @param EntiteRepository $repositoryEntite
     * @param HistoriqueRepository $repositoryHistorique
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ActionRepository $repositoryAction, ContainerInterface $container, EntiteAdmin $sfServEntiteAdmin, EntityManagerInterface $entityManager, EntiteRepository $repositoryEntite, Historique $sfServHistorique, HistoriqueRepository $repositoryHistorique, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($container, $entityManager, $tokenStorage);
        $this->repositoryAction = $repositoryAction;
        $this->repositoryEntite = $repositoryEntite;
        $this->repositoryHistorique = $repositoryHistorique;
        $this->sfServEntiteAdmin = $sfServEntiteAdmin;
        $this->sfServHistorique = $sfServHistorique;
    }

    /**
     * cherche l'entite "Action"
     * si l'entite "Action" n'existe pas demande la creation de l'entite "Action" et la creation de l'historique de l'entité "Action"
     * persiste, flush l'Action passée en paramètre
     * demande la création de l'historique de l'action "Action"
     *
     * @param Action $action
     *
     * @throws Exception
     */
    public function cree(Action $action = null)
    {
        try{
            $this->entityManager->beginTransaction();
        if (empty($action)) {
            $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
        }
        if (empty($action)) {
            $action = new Action();
            $action->setLibelle('create');
        }
        $this->entityManager->persist($action);
        $this->entityManager->flush();
        $entiteAction = $this->repositoryEntite->findOneBy(['libelle' => Action::class]);
        if (empty($entiteAction)) {
            $this->sfServEntiteAdmin->cree();
            $entiteAction = $this->repositoryEntite->findOneBy(['libelle' => Action::class]);
        }
        $actionCreate = $this->repositoryAction->findOneBy(['libelle' => 'create']);
        $this->sfServHistorique->cree($actionCreate, $entiteAction, $action);
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
        return $this->repositoryHistorique->findByAction();
    }
}