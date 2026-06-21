<?php

declare(strict_types=1);

namespace App\Application\Password;

use App\Domain\ValueObject\User\EmptyPassword;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PasswordInterface;

class HashedPasswordStrategy implements PasswordStrategyInterface
{
    public function getModifiedVersion(Password $password): string
    {
        return password_hash($password->getValue(), PASSWORD_BCRYPT);
    }

    public function getPasswordFromModifiedVersion(string $data): PasswordInterface
    {
        return new EmptyPassword();
    }

    public function verifyPasswordEqualsPasswordInModifiedVersion(PasswordInterface $password, string $data): bool
    {
        return password_verify($password->getValue(), $data);
    }

    public function getStrategyName(): string
    {
        return self::STRATEGY_HASHED;
    }
}
