<?php
namespace App\Service;

use App\Entity\Action;
use App\Entity\Culture;
use App\Entity\Entite;
use App\Service\Historique as SfServHistorique;
use App\Interfaces\EntiteInterface;
use App\Interfaces\Historisable;
use App\Repository\ActionRepository;
use App\Repository\CultureRepository;
use App\Repository\EntiteRepository;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use \Exception;

/**
 * Class CultureAdmin
 * @package App\Service
 */
class CultureAdmin extends ServiceParent
{
    /** @var CultureRepository  */
    private $repositoryCulture;

    /** @var ActionRepository  */
    private $repositoryAction;

    /** @var HistoriqueRepository  */
    private $repositoryHistorique;

    /** @var EntiteAdmin  */
    private $sfServEntite;

    /**
     * CultureAdmin constructor.
     *
     * @param ActionRepository $repositoryAction
     * @param ContainerInterface $container
     * @param CultureRepository $repositoryCulture
     * @param EntiteAdmin $sfServEntite
     * @param EntityManagerInterface $entityManager
     * @param EntiteRepository $repositoryEntite
     * @param Historique $sfServHistorique
     * @param HistoriqueRepository $repositoryHistorique
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ActionRepository  $repositoryAction, ContainerInterface $container, CultureRepository $repositoryCulture, EntiteAdmin $sfServEntite, EntityManagerInterface $entityManager, EntiteRepository $repositoryEntite, SfServHistorique $sfServHistorique, HistoriqueRepository $repositoryHistorique, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($container, $entityManager, $tokenStorage);
        $this->repositoryCulture = $repositoryCulture;
        $this->repositoryAction = $repositoryAction;
        $this->repositoryEntite= $repositoryEntite;
        $this->repositoryHistorique = $repositoryHistorique;
        $this->sfServEntite = $sfServEntite;
        $this->sfServHistorique = $sfServHistorique;
    }

    /**
     * cherche l'action "create"
     * si ne trouve pas l'action "create" demande la creation de l'action "create" et demande la création de l'historique de l'action "create"
     * cherche l'entite "Culture"
     * si l'entite "Culture" n'existe pas demande la création de l'entite "Culture" et demande la creation de l'historique de l'entité "Culture"
     * cherche la culture "Culture"
     * si la culture "Culture" n'existe pas crée la culture "Culture" et demande la creation de l'historique de la culture "Culture"
     *
     * @param EntiteInterface $culture
     *
     * @throws Exception
     */
    public function cree(Historisable $culture)
    {
        try{
            $this->entityManager->beginTransaction();
            $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
            if (empty($action)) {
                $sfServAction = $this->container->get(ActionAdmin::class);
                $sfServAction->cree();
                $action = $this->repositoryAction->findOneBy(['libelle' => 'create']);
            }
            $entiteAction = $this->repositoryEntite->findOneBy(['libelle' => Action::class]);
            $entiteEntite = $this->repositoryEntite->findOneBy(['libelle' => Entite::class]);
            if (empty($entiteEntite) || empty($entiteAction)) {
                $this->sfServEntite->cree();
                $entiteEntite = $this->repositoryEntite->findOneBy(['libelle' => Entite::class]);
            }
            $entiteCulture = $this->repositoryEntite->findOneBy(['libelle' => Culture::class]);
            if (empty($entiteCulture)) {
                $entiteCulture = new Entite();
                $entiteCulture->setLibelle(Culture::class);
                $this->entityManager->persist($entiteCulture);
                $this->sfServHistorique->cree($action, $entiteEntite, $entiteCulture);
            }
            $this->entityManager->persist($culture);
            $this->entityManager->flush();
            $this->sfServHistorique->cree($action, $entiteCulture, $culture);
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
        return $this->repositoryHistorique->findByCulture();
    }
}