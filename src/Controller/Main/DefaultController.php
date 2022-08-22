<?php

namespace App\Controller\Main;

use App\Entity\Product;
use App\Entity\User;
use http\Env\Request as EnvRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'main_homepage')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $productList = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('main/default/index.html.twig', []);
    }

    #[Route('/all_users', name: 'all_users')]
    public function verify(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->findAll();
//        $user->setIsVerified(true);
//        $entityManager->persist($user);
//        $entityManager->flush();
        dd($user);
    }
}
