<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/admin/test', name: 'app_admin_test')]
    public function showAction(
        Request $request,
    ) {

        return $this->render('admin/test/index.html.twig');
    }
}
