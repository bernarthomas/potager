<?php

namespace App\Controller;

use App\Entity\Action;
use App\Form\ActionType;
use App\Service\ActionAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActionAdminController
 * @package App\Controller
 */
class ActionAdminController extends AbstractController
{
    /** @var ActionAdmin  */
    private $sfServActionAdmin;

    /**
     * ActionAdminController constructor.
     *
     * @param ActionAdmin $sfServActionAdmin
     */
    public function __construct(ActionAdmin $sfServActionAdmin)
    {
        $this->sfServActionAdmin = $sfServActionAdmin;
    }

    /**
     * @Route("/tableau_de_bord/action", name="action_index")
     *
     * @return Response
     */
    public function index()
    {
        $action = new Action();
        $formCreer = $this->createForm(ActionType::class, $action, ['action' => $this->generateUrl('action_ajouter')]);
        $liste = $this->sfServActionAdmin->liste();
        $historique = $this->sfServActionAdmin->historique();

        return $this->render('admin/action_admin.html.twig', [
            'form_creer' => $formCreer->createView(),
            'historique' => $historique,
            'liste' => $liste,
            'controller_name' => 'ActionAdminController',
        ]);
    }

    /**
     * @Route("/tableau_de_bord/action/ajouter", name="action_ajouter")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function ajouter(Request $request)
    {
        $action = new Action();
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->sfServActionAdmin->cree($form->getData());
        }

        return $this->redirect($this->generateUrl('action_index'));
    }
}