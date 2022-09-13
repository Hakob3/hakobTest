<?php

namespace App\Logger;

use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionRequestProcessor
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    // this method is called for each log record; optimize it to not hurt performance
    public function __invoke(LogRecord $record)
    {
        try {
            $session = $this->requestStack->getSession();
        } catch (SessionNotFoundException $e) {
            return;
        }
        if (!$session->isStarted()) {
            return $record;
        }

        $sessionId = substr($session->getId(), 0, 8) ?: '????????';

        $record['extra']['token'] = $sessionId.'-'.substr(uniqid('', true), -8);

        return $record;
    }
}