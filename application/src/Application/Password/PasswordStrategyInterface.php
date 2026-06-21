<?php

declare(strict_types=1);

namespace App\Application\Password;

use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PasswordInterface;

interface PasswordStrategyInterface
{
    public const STRATEGY_PLAIN = 'plain';
    public const STRATEGY_HASHED = 'hashed';
    public const STRATEGY_ENCRYPTED = 'encrypted';

    public function getModifiedVersion(Password $password): string;
    public function getPasswordFromModifiedVersion(string $data): PasswordInterface;
    public function verifyPasswordEqualsPasswordInModifiedVersion(PasswordInterface $password, string $data): bool;
    public function getStrategyName(): string;
}
