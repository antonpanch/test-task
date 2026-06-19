<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Authorization;

use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Infrastructure\Api\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends ApiController
{
    public const OUTPUT_DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function generateToken(): Response
    {
        $fields = $this->getFieldsFromJsonRequestContent();
        $login = $fields['login'] ?? '';
        $password = $fields['pass'] ?? '';

        $bearerToken = $this->authService->generateBearerByLoginAndPassword(new Login($login), new Password($password));
        $data = [
            'token' => $bearerToken->getToken(),
            'expirationTime' => $bearerToken->getExpirationDate()->format(self::OUTPUT_DATETIME_FORMAT)
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
