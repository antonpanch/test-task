<?php

namespace App\Application\Permission;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Pagination\AfterId;
use App\Domain\ValueObject\Pagination\PerPage;
use App\Domain\ValueObject\Role\Permission;
use App\Domain\ValueObject\User\UserId;

class GetUsersIfAllowedUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function handle(User $loggedInUser, AfterId $afterId, PerPage $perPage): array
    {
        if ($loggedInUser->hasPermission(Permission::GET_ANY_USER)) {
            return $this->userRepository->getAll($afterId, $perPage);
        }
        if (!$loggedInUser->hasPermission(Permission::UPDATE_SELF_USER)) {
            return [];
        }
        if ($afterId->getValue() >= $loggedInUser->getId()) {
            return [];
        }
        return [ $this->userRepository->findById(new UserId($loggedInUser->getId())) ];
    }
}
