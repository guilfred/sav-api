<?php

// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!($exception instanceof AccessDeniedHttpException)) {
            return;
        }

        $response = new JsonResponse();
        $response->setData(['code' => $exception->getStatusCode(), 'message' => $exception->getMessage()]);
        $response->setStatusCode($exception->getStatusCode());
        $response->headers->replace($exception->getHeaders());

        $event->setResponse($response);
    }
}
