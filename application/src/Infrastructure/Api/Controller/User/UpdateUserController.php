<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\User;

use App\Application\Permission\UpdateUserIfAllowedUseCase;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserController extends AbstractUserController
{
    public function updateUser(UpdateUserIfAllowedUseCase $updateUserIfAllowedUseCase): Response
    {
        $userId = $this->request->query->get('id') ?? '';
        $fields = $this->getFieldsFromJsonRequestContent();
        $login = $fields['login'] ?? '';
        $password = $fields['pass'] ?? '';
        $phoneNumber = $fields['phone'] ?? '';
        $user = $updateUserIfAllowedUseCase->handle(
            $this->loggedInUser,
            new UserId($userId),
            new Login($login),
            new Password($password),
            new PhoneNumber($phoneNumber)
        );
        return new JsonResponse(
            $this->formatUserDataForResponse($user),
            Response::HTTP_OK
        );
    }
}
