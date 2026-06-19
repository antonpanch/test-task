<?php

declare(strict_types=1);

namespace App\Application\Permission;

use App\Application\User\CreateUserUseCase;
use App\Domain\Entity\User;
use App\Domain\Exception\PermissionException;
use App\Domain\ValueObject\Role\Permission;
use App\Domain\ValueObject\Role\RoleName;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;

class CreateUserIfAllowedUseCase
{
    public function __construct(private readonly CreateUserUseCase $createUserUseCase)
    {
    }

    public function handle(
        User $currentUser,
        Login $login,
        Password $password,
        PhoneNumber $phoneNumber,
        string $roleName = RoleName::ROLE_NAME_USER
    ): User {
        if (!$currentUser->hasPermission(Permission::CREATE_USER)) {
            throw new PermissionException("Not enough permissions to crete new user");
        }
        return $this->createUserUseCase->handle($login, $password, $phoneNumber, $roleName);
    }
}
