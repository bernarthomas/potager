<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableauDeBordController extends AbstractController
{
    /**
     * @Route("/tableau_de_bord", name="tableau_de_bord")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('tableau_de_bord/index.html.twig', [
            'controller_name' => 'TableauDeBordController',
        ]);
    }
}