<?php

namespace App\Controller;

use App\Entity\Recolte;
use App\Form\RecolteType;
use App\Service\CultureAdmin;
use App\Service\RecolteAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecolteAdminController extends AbstractController
{
    /** @var CultureAdmin  */
    private $sfServRecolteAdmin;

    /**
     * RecolteAdminController constructor.
     *
     * @param RecolteAdmin $sfServRecolteAdmin
     */
    public function __construct(RecolteAdmin $sfServRecolteAdmin)
    {
        $this->sfServRecolteAdmin = $sfServRecolteAdmin;
    }

    /**
     * @Route("/tableau_de_bord/recolte", name="recolte_index")
     *
     * @return Response
     */
    public function index()
    {
        $recolte = new Recolte();
        $formCreer = $this->createForm(RecolteType::class, $recolte, ['action' => $this->generateUrl('recolte_ajouter')]);
        $liste = $this->sfServRecolteAdmin->liste();
        $historique = $this->sfServRecolteAdmin->historique();

        return $this->render('admin/recolte_admin.html.twig', [
            'form_creer' => $formCreer->createView(),
            'historique' => $historique,
            'liste' => $liste,
            'controller_name' => 'RecolteAdminController',
        ]);
    }

    /**
     * @Route("/tableau_de_bord/recolte/ajouter", name="recolte_ajouter")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function ajouter(Request $request)
    {
        $recolte = new Recolte();
        $form = $this->createForm(RecolteType::class, $recolte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->sfServRecolteAdmin->cree($form->getData());
        }

        return $this->redirect($this->generateUrl('recolte_index'));
    }
}