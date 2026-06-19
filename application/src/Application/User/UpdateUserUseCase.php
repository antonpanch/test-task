<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;

class UpdateUserUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function handle(UserId $userId, Login $login, Password $password, PhoneNumber $phoneNumber): User
    {
        return $this->userRepository->update(
            $userId,
            $login,
            $password,
            $phoneNumber
        );
    }
}
