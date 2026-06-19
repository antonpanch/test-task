<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\User\UserId;

class DeleteUserUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function handle(UserId $userId): void
    {
        $this->userRepository->delete($userId);
    }
}
