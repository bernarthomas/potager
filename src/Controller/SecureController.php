<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecureController extends AbstractController
{
    /**
     * @Route("/secure", name="secure")
     */
    public function index()
    {
        return $this->render('secure/index.html.twig', [
            'controller_name' => 'SecureController',
        ]);
    }
}
