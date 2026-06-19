<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Authorization\AuthService;
use App\Domain\Exception\PermissionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class ApiController
{
    private const ALLOWED_CONTENT_TYPE_FORMATS = ['json'];

    protected readonly AuthService $authService;
    protected readonly Request $request;

    public function __construct(
        AuthService $authService,
        RequestStack $requestStack
    ) {
        $this->authService = $authService;
        $this->request = $requestStack->getCurrentRequest();
        $this->checkContentTypeFormat();
    }

    protected function checkContentTypeFormat(): void
    {
        $format = $this->request->getContentTypeFormat();
        if (
            !in_array($format, self::ALLOWED_CONTENT_TYPE_FORMATS)
        ) {
            throw new PermissionException(
                sprintf(
                    "Only request with next content-type formats are supported: %s",
                    implode(',', self::ALLOWED_CONTENT_TYPE_FORMATS)
                )
            );
        }
    }

    protected function getFieldsFromJsonRequestContent(): array
    {
        return json_decode($this->request->getContent(), true);
    }
}
