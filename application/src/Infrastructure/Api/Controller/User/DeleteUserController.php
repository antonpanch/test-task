<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\User;

use App\Application\Permission\DeleteUserIfAllowedUseCase;
use App\Domain\ValueObject\User\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserController extends AbstractUserController
{
    public function deleteUser(DeleteUserIfAllowedUseCase $deleteUserIfAllowedUseCase): Response
    {
        $userId = $this->request->query->get('id');
        $deleteUserIfAllowedUseCase->handle($this->loggedInUser, new UserId($userId));
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
