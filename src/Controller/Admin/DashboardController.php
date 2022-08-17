<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\BlogPost;
use App\Entity\Category;
use App\Entity\Comment;
use App\Repository\RecordRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function home(RecordRepository $recordRepository): Response
    {
        $records = [];

        $recordsRaw = $recordRepository->findAll();
        foreach ($recordsRaw as $recordRaw) {
            $records[] = [
                'id' => $recordRaw->getId(),
                'text' => $recordRaw->getText(),
                'email' => $recordRaw->getEmail(),
                'image' => implode(', ', $recordRaw->getImages()),
                'color' => $recordRaw->getColor()->getColor(),
                'geometry' => $recordRaw->getGeometry()->getGeometry()
            ];
        }

        return $this->render('admin/test.html.twig', [
            'records' => $records
        ]);
    }

    #[Route('/record/{id}', name: 'record_id')]
    public function task_success($id, RecordRepository $recordRepository): Response
    {
        $recordRaw = $recordRepository->find($id);

        $record = [
            'id' => $recordRaw->getId(),
            'text' => $recordRaw->getText(),
            'email' => $recordRaw->getEmail(),
            'image' => implode(', ', $recordRaw->getImages()),
            'color' => $recordRaw->getColor()->getColor(),
            'geometry' => $recordRaw->getGeometry()->getGeometry()
        ];

        return $this->render('admin/record.html.twig', [
            'record' => $record,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Planningpro Ru');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Blog'),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
            MenuItem::linkToCrud('Blog Posts', 'fa fa-file-text', BlogPost::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
        ];
    }
}
