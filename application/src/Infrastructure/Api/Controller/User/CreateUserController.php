<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\User;

use App\Application\Permission\CreateUserIfAllowedUseCase;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateUserController extends AbstractUserController
{
    public function createUser(CreateUserIfAllowedUseCase $createUserIfAllowedUseCase): Response
    {
        $fields = $this->getFieldsFromJsonRequestContent();
        $login = $fields['login'] ?? '';
        $password = $fields['pass'] ?? '';
        $phone = $fields['phone'] ?? '';

        $user = $createUserIfAllowedUseCase->handle(
            $this->loggedInUser,
            new Login($login),
            new Password($password),
            new PhoneNumber($phone)
        );
        return new JsonResponse($this->formatUserDataForResponse($user), Response::HTTP_CREATED);
    }
}
