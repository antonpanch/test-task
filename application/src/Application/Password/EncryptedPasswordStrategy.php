<?php

declare(strict_types=1);

namespace App\Application\Password;

use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PasswordInterface;

class EncryptedPasswordStrategy implements PasswordStrategyInterface
{
    public function __construct(private readonly PasswordEncrypter $passwordEncrypter)
    {
    }

    public function getModifiedVersion(Password $password): string
    {
        return $this->passwordEncrypter->encryptPassword($password);
    }

    public function getPasswordFromModifiedVersion(string $data): PasswordInterface
    {
        return $this->passwordEncrypter->decryptPassword($data);
    }

    public function verifyPasswordEqualsPasswordInModifiedVersion(PasswordInterface $password, string $data): bool
    {
        return $password->getValue() === $this->getPasswordFromModifiedVersion($data)->getValue();
    }

    public function getStrategyName(): string
    {
        return self::STRATEGY_ENCRYPTED;
    }
}
