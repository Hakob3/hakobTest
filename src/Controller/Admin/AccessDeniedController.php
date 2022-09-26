<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccessDeniedController
 * @package App\Controller\Admin
 */
#[IsGranted('ROLE_USER')]
class AccessDeniedController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/access_denied', name: 'app_access_denied')]
    public function index(): Response
    {
        return $this->render('admin/access_denied/index.html.twig', [
            'controller_name' => 'AccessDeniedController',
        ]);
    }
}
