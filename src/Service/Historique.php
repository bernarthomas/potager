<?php
namespace App\Service;

use App\Entity\Action;
use App\Entity\Entite;
use App\Entity\Utilisateur;
use App\Interfaces\EntiteInterface;
use App\Interfaces\Historisable;
use \DateTime;
use \Exception;
use App\Entity\Historique as EntiteHistorique;

/**
 * Class Historique
 * @package App\Service
 */
class Historique extends ServiceParent
{
    /**
     * créé l'historique de l'objet passé en parametre
     *
     * @param Action $action
     *
     * @throws Exception
     */
    public function cree(Action $action, Entite $entite, Historisable $objet, $flush = true)
    {
        try{
            $this->entityManager->beginTransaction();
            $maintenant = new DateTime();
            /** @var Utilisateur $utilisateur */
            $utilisateur = $this->tokenStorage->getToken()->getUser();
            $historique = new EntiteHistorique();
            $historique
                ->setAction($action)
                ->setDate($maintenant)
                ->setEntite($entite)
                ->setOccurenceId($objet->getId())
                ->setUtilisateur($utilisateur)
                ->setValeursModifiees($objet->toArray())
            ;
            $this->entityManager->persist($historique);
            if (true === $flush) {
                $this->entityManager->flush();
            }
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
        }
    }
}