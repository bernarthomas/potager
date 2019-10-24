<?php

namespace App\Controller;

use App\Entity\Entite;
use App\Form\EntiteType;
use App\Service\EntiteAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntiteAdminController extends AbstractController
{
    /** @var EntiteAdmin  */
    private $sfServEntiteAdmin;

    /**
     * EntiteAdminController constructor.
     *
     * @param EntiteAdmin $sfServEntiteAdmin
     */
    public function __construct(EntiteAdmin $sfServEntiteAdmin)
    {
        $this->sfServEntiteAdmin = $sfServEntiteAdmin;
    }

    /**
     * @Route("/tableau_de_bord/entite", name="entite_index")
     *
     * @return Response
     */
    public function index()
    {
        $entite = new Entite();
        $formCreer = $this->createForm(EntiteType::class, $entite, ['action' => $this->generateUrl('entite_ajouter')]);
        $liste = $this->sfServEntiteAdmin->liste();
        $historique = $this->sfServEntiteAdmin->historique();

        return $this->render('admin/entite_admin.html.twig', [
            'form_creer' => $formCreer->createView(),
            'historique' => $historique,
            'liste' => $liste,
            'controller_name' => 'EntiteAdminController',
        ]);
    }

    /**
     * @Route("/tableau_de_bord/entite/ajouter", name="entite_ajouter")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function ajouter(Request $request)
    {
        $entite = new Entite();
        $form = $this->createForm(EntiteType::class, $entite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->sfServEntiteAdmin->cree($form->getData());
        }

        return $this->redirect($this->generateUrl('entite_index'));
    }
}