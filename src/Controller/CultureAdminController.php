<?php

namespace App\Controller;

use App\Entity\Culture;
use App\Form\CultureType;
use App\Service\CultureAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Exception;

/**
 * Class CultureAdminController
 * @package App\Controller
 */
class CultureAdminController extends AbstractController
{
    /** @var CultureAdmin  */
    private $sfServCultureAdmin;

    /**
     * CultureAdminController constructor.
     *
     * @param CultureAdmin $sfServCultureAdmin
     */
    public function __construct(CultureAdmin $sfServCultureAdmin)
    {
        $this->sfServCultureAdmin = $sfServCultureAdmin;
    }

    /**
     * @Route("/tableau_de_bord/culture", name="culture_index")
     *
     * @return Response
     */
    public function index()
    {
        $culture = new Culture();
        $formCreer = $this->createForm(CultureType::class, $culture, ['action' => $this->generateUrl('culture_ajouter')]);
        $liste = $this->sfServCultureAdmin->liste();
        $historique = $this->sfServCultureAdmin->historique();

        return $this->render('admin/culture_admin.html.twig', [
            'form_creer' => $formCreer->createView(),
            'historique' => $historique,
            'liste' => $liste,
            'controller_name' => 'CultureAdminController',
        ]);
    }

    /**
     * @Route("/tableau_de_bord/culture/ajouter", name="culture_ajouter")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws Exception
     */
    public function ajouter(Request $request)
    {
        $culture = new Culture();
        $form = $this->createForm(CultureType::class, $culture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->sfServCultureAdmin->cree($form->getData());
        }

        return $this->redirect($this->generateUrl('culture_index'));
    }
}