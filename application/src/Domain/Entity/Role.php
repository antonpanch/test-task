<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Role\Permission;
use App\Domain\ValueObject\Role\RoleId;
use App\Domain\ValueObject\Role\RoleName;

class Role
{
    private const PERMISSIONS_BY_ROLE_NAMES = [
        RoleName::ROLE_NAME_ROOT => [
            Permission::CREATE_USER,
            Permission::UPDATE_ANY_USER,
            Permission::GET_ANY_USER,
            Permission::DELETE_ANY_USER
        ],
        RoleName::ROLE_NAME_USER => [
            Permission::CREATE_USER,
            Permission::GET_SELF_USER,
            Permission::UPDATE_SELF_USER
        ]
    ];

    public function __construct(
        private readonly RoleName $name,
        private readonly RoleId $id
    ) {
    }

    public function getName(): string
    {
        return $this->name->getValue();
    }

    public function getId(): int
    {
        return $this->id->getValue();
    }

    public function hasPermission(Permission $permission): bool
    {
        return in_array($permission, self::PERMISSIONS_BY_ROLE_NAMES[$this->getName()]);
    }
}
