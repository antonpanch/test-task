<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Pagination\AfterId;
use App\Domain\ValueObject\Pagination\PerPage;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;

interface UserRepositoryInterface
{
    public function create(User $user): User;
    public function findByLoginAndPassword(Login $login, Password $password): User;
    public function findById(UserId $id): User;
    public function delete(UserId $userId): void;
    public function update(UserId $userId, Login $login, Password $password, PhoneNumber $phoneNumber): User;
    public function getAll(AfterId $afterId, PerPage $perPage): array;
}
