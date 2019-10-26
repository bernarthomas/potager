<?php

namespace App\Controller;

use App\Repository\Recolte2019Repository;
use App\Service\RecolteAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PrincipalController extends AbstractController
{
    /** @var Recolte2019Repository  */
    private $repositoryRecolte2019;

    /** @var RecolteAdmin  */
    private $sfServRecolte;

    /**
     * PrincipalController constructor.
     *
     * @param RecolteAdmin $sfServRecolteAdmin
     */
    public function __construct(RecolteAdmin $sfServRecolte, Recolte2019Repository $repositoryRecolte2019)
    {
        $this->repositoryRecolte2019 = $repositoryRecolte2019;
        $this->sfServRecolte = $sfServRecolte;
    }

    /**
     * @Route("/", name="principal")
     */
    public function index()
    {
        $recoltes2019 = $this->repositoryRecolte2019->findAll();
        $recoltes2019Decorees = $this->sfServRecolte->setRecoltes($recoltes2019)->decore();

        return $this->render('principal/index.html.twig', [
            'controller_name' => 'PrincipalController',
            'recoltes2019' => $recoltes2019Decorees
        ]);
    }
}
