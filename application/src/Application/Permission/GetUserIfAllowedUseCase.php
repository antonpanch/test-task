<?php

declare(strict_types=1);

namespace App\Application\Permission;

use App\Application\User\GetUserUseCase;
use App\Domain\Entity\User;
use App\Domain\Exception\PermissionException;
use App\Domain\ValueObject\Role\Permission;
use App\Domain\ValueObject\User\UserId;

class GetUserIfAllowedUseCase
{
    public function __construct(private readonly GetUserUseCase $getUserUseCase)
    {
    }

    public function handle(
        User $currentUser,
        UserId $userId
    ): User {
        if (!$this->canGetUser($currentUser, $userId->getValue())) {
            throw new PermissionException("Not enough permissions to get the information about specified user");
        }
        return $this->getUserUseCase->handle($userId);
    }

    private function canGetUser(User $currentUser, int $userId): bool
    {
        if ($currentUser->hasPermission(Permission::GET_ANY_USER)) {
            return true;
        }
        if (!$currentUser->hasPermission(Permission::GET_SELF_USER)) {
            return false;
        }
        return $currentUser->getId() === $userId;
    }
}
