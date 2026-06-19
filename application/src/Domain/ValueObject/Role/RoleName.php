<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Role;

use App\Domain\Exception\DomainValidationException;

class RoleName
{
    public const ROLE_NAME_ROOT = 'root';
    public const ROLE_NAME_USER = 'user';

    public const ROLE_NAMES = [
        self::ROLE_NAME_ROOT,
        self::ROLE_NAME_USER
    ];

    private readonly string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::ROLE_NAMES)) {
            throw new DomainValidationException(
                sprintf(
                    "Wrong role name provided: %s. Available role names: %s",
                    $value,
                    implode(", ", self::ROLE_NAMES)
                )
            );
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
