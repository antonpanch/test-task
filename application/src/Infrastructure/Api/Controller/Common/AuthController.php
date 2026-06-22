<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Common;

use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Infrastructure\Api\Controller\ApiController;
use App\Infrastructure\Api\Formatter\TokenFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends ApiController
{
    public function generateToken(): Response
    {
        $fields = $this->getFieldsFromJsonRequestContent();
        $bearerToken = $this->authService->generateBearerByLoginAndPassword(
            new Login($fields['login'] ?? ''),
            new Password($fields['pass'] ?? '')
        );
        return new JsonResponse(
            (new TokenFormatter())->formatToken($bearerToken),
            Response::HTTP_OK
        );
    }
}
