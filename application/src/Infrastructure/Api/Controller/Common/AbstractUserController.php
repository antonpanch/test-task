<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Common;

use App\Application\Authorization\AuthService;
use App\Domain\Entity\User;
use App\Infrastructure\Api\Controller\ApiController;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractUserController extends ApiController
{
    protected readonly User $loggedInUser;

    public function __construct(AuthService $authService, RequestStack $requestStack)
    {
        parent::__construct($authService, $requestStack);
        $this->loggedInUser = $this->getLoggedInUser();
    }

    private function getLoggedInUser(): User
    {
        $token = $this->retrieveBearerToken();
        return $this->authService->getUserByToken($token);
    }

    private function retrieveBearerToken(): string
    {
        $authorizationHeader = $this->request->headers->get('Authorization');
        return str_replace('Bearer ', '', $authorizationHeader);
    }
}
