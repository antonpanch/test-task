<?php

declare(strict_types=1);

namespace App\Application\Permission;

use App\Application\User\UpdateUserUseCase;
use App\Domain\Entity\User;
use App\Domain\Exception\PermissionException;
use App\Domain\ValueObject\Role\Permission;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;

class UpdateUserIfAllowedUseCase
{
    public function __construct(private readonly UpdateUserUseCase $updateUserUseCase)
    {
    }

    public function handle(
        User $currentUser,
        UserId $userId,
        Login $login,
        Password $password,
        PhoneNumber $phone
    ): User {
        if (!$this->canUpdateUser($currentUser, $userId->getValue())) {
            throw new PermissionException('Not enough permission to edit the specified user');
        }
        return $this->updateUserUseCase->handle($userId, $login, $password, $phone);
    }

    private function canUpdateUser(User $currentUser, int $userId): bool
    {
        if ($currentUser->hasPermission(Permission::UPDATE_ANY_USER)) {
            return true;
        }
        if (!$currentUser->hasPermission(Permission::UPDATE_SELF_USER)) {
            return false;
        }
        return $currentUser->getId() === $userId;
    }
}
