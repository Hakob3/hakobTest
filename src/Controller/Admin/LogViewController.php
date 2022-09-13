<?php

namespace App\Controller\Admin;

use Evotodi\LogViewerBundle\Service\LogList;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Evotodi\LogViewerBundle\Reader\LogReader;
use Exception;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;
use Twig\Environment;

class LogViewController extends AbstractController
{
    private LogList $logList;
    private Environment $twig;

    public function __construct(LogList $logList, Environment $twig)
    {
        $this->logList = $logList;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param int $id
     * @return Response
     */
    #[Route(path: '/admin/log_view/{id}', name: 'admin_log_view')]
    public function logViewAction(
        Request $request,
        PaginatorInterface $paginator,
        int $id
    ): Response {
        $delete = filter_var($request->query->get('delete'), FILTER_VALIDATE_BOOLEAN);
        $logs = $this->logList->getLogList();
        $context = [];

        if (!file_exists($logs[$id]->getPath())) {
            throw new FileNotFoundException(sprintf("Log file \"%s\" was not found!", $logs[$id]['path']));
        }

        if ($delete) {
            unlink($logs[$id]->getPath());
            return $this->redirectToRoute('_log_viewer_list');
        }

        $reader = new LogReader($logs[$id]);

        if (!is_null($logs[$id]->getPattern())) {
            $reader->getParser()->registerPattern('NewPattern', $logs[$id]->getPattern());
            $reader->setPattern('NewPattern');
        }

        $lines = [];
        $message = '';
        $filter = $request->request->get('levelFilter');
        foreach ($reader as $line) {
            $levelFilter = isset($line['level']) && $filter ? $request->request->get($line['level']) : 'on';
            if ($levelFilter) {
                if (isset($line['message']) && preg_match('/\.*\{(\{.*\})\}.*/', $line['message'], $matches)) {
                    $message = str_replace($matches[0], '', $line['message']);
                };
                try {
                    $lines[] = [
                        'lineId' => uniqid(),
                        'dateTime' => $line['date'],
                        'channel' => $line['channel'],
                        'level' => $line['level'],
                        'message' => $message,
                        'context' => $matches[1]
                    ];
                } catch (Exception $e) {
                    continue;
                }
            };
        };

        usort($lines, fn($a, $b) => $b['dateTime'] <=> $a['dateTime']);

        if (!empty($lines)) {
            if ($this->logList->getLogsReverse()) {
                $context['log'] = array_reverse($lines);
            } else {
                $context['log'] = $lines;
            }
        } else {
            $context['noLog'] = true;
        }

        $context['levels'] = $logs[$id]->getLevels();
        $context['use_channel'] = $logs[$id]->isUseChannel();
        $context['use_level'] = $logs[$id]->isUseLevel();

        return $this->render('admin/logs/log_view/log_view.html.twig', $context);
    }
}
