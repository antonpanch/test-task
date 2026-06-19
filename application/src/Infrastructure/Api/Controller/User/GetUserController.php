<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\User;

use App\Application\Permission\GetUserIfAllowedUseCase;
use App\Domain\ValueObject\User\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetUserController extends AbstractUserController
{
    public function getUser(GetUserIfAllowedUseCase $getUserIfAllowedUseCase): Response
    {
        $id = $this->request->query->get('id')
            ? (int) $this->request->query->get('id')
            : null;
        $user = $getUserIfAllowedUseCase->handle($this->loggedInUser, new UserId($id));
        return new JsonResponse($this->formatUserDataForResponse($user), Response::HTTP_OK);
    }
}
