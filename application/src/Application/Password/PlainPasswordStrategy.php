<?php

declare(strict_types=1);

namespace App\Application\Password;

use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PasswordInterface;

class PlainPasswordStrategy implements PasswordStrategyInterface
{
    public function getModifiedVersion(Password $password): string
    {
        return $password->getValue();
    }

    public function getPasswordFromModifiedVersion(string $data): PasswordInterface
    {
        return new Password($data);
    }

    public function verifyPasswordEqualsPasswordInModifiedVersion(PasswordInterface $password, string $data): bool
    {
        return $password->getValue() === $data;
    }

    public function getStrategyName(): string
    {
        return self::STRATEGY_PLAIN;
    }
}
