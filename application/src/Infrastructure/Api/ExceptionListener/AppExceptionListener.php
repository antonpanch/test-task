<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\ExceptionListener;

use App\Domain\Exception\DomainValidationException;
use App\Domain\Exception\PermissionException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: ExceptionEvent::class, method: 'onException')]
class AppExceptionListener
{
    public function onException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if ($throwable instanceof DomainValidationException) {
            $statusCode = Response::HTTP_BAD_REQUEST;
            $message = $throwable->getMessage();
        } elseif ($throwable instanceof PermissionException) {
            $statusCode = Response::HTTP_FORBIDDEN;
            $message = $throwable->getMessage();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'An error occurred';
        }
        $data = ['error' => $message];
        $event->setResponse(new JsonResponse($data, $statusCode));
    }
}
