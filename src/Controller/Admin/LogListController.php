<?php

namespace App\Controller\Admin;

use Evotodi\LogViewerBundle\Service\LogList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Environment;

class LogListController extends AbstractController
{
    private LogList $logList;
    private Environment $twig;

    public function __construct(LogList $logList, Environment $twig)
    {
        $this->logList = $logList;
        $this->twig = $twig;
    }

    /**
     * @return Response
     */
    #[Route('/admin/log/list', name: 'app_admin_log_list')]
    public function logListAction(): Response
    {
        $logs = $this->logList->getLogList();
        return new Response($this->twig->render('admin/logs/log_list/log_list.html.twig', [
            'logs' => $logs
        ]));
    }
}

