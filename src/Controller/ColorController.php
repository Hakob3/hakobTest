<?php

namespace App\Controller;

use App\Entity\Colors;
use App\Entity\Geometry;
use App\Repository\ColorsRepository;
use App\Form\Type\RecordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ColorController extends AbstractController
{
    private static $colors = [];

    /**
     * @Route("/color", name="create_color")
     */
    public function createColor(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();

        $color = new Geometry();
        $color->setGeometry('triangle');

        $errors = $validator->validate($color);
        if (count($errors) > 0) {
            return new Response((string)$errors, 400);
        }

        $entityManager->persist($color);

        $entityManager->flush();

        return new Response('Saved new color with id ' . $color->getId());
    }

    /**
     * @Route("/getColors", name="color_show")
     */
    public function show(ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Colors::class);

        $colorsArr = [];

        $colors = $repository->findAtColumn();

        foreach ($colors as $color) {
            $colorsArr[] = $color['color'];
        }

        return $colorsArr;
    }

    public function getColors(ManagerRegistry $registry)
    {
        return $this->show($registry);
    }
}
