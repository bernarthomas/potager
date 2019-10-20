<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntiteAdminController extends AbstractController
{
    /**
     * @Route("/tableau_de_bord/entite_admin", name="entite_admin")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('admin/entite_admin.html.twig', [
            'controller_name' => 'EntiteAdminController',
        ]);
    }
}