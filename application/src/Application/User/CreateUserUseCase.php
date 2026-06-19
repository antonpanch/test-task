<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\Entity\User;
use App\Domain\Repository\RoleRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Role\RoleName;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;

class CreateUserUseCase
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(
        Login $login,
        Password $password,
        PhoneNumber $phoneNumber,
        string $roleName = RoleName::ROLE_NAME_USER
    ): User {
        $role = $this->roleRepository->getByName(new RoleName($roleName));
        $user = new User(
            $login,
            $password,
            $phoneNumber,
            $role
        );
        return $this->userRepository->create($user);
    }
}
