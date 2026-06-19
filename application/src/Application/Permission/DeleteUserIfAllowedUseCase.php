<?php

declare(strict_types=1);

namespace App\Application\Permission;

use App\Application\User\DeleteUserUseCase;
use App\Domain\Entity\User;
use App\Domain\Exception\PermissionException;
use App\Domain\ValueObject\Role\Permission;
use App\Domain\ValueObject\User\UserId;

class DeleteUserIfAllowedUseCase
{
    public function __construct(private readonly DeleteUserUseCase $deleteUserUseCase)
    {
    }

    public function handle(User $currentUser, UserId $userId): void
    {
        if (!$currentUser->hasPermission(Permission::DELETE_ANY_USER)) {
            throw new PermissionException('Not enough permission to delete the user');
        }
        $this->deleteUserUseCase->handle($userId);
    }
}
