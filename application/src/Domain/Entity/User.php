<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Role\Permission;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;

class User
{
    private readonly Login $login;
    private readonly Password $password;
    private readonly PhoneNumber $phoneNumber;
    private readonly Role $role;
    private ?UserId $id;

    public function __construct(
        Login $login,
        Password $password,
        PhoneNumber $phoneNumber,
        Role $role,
        ?UserId $id = null
    ) {
        $this->login = $login;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
        $this->role = $role;
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login->getValue();
    }

    public function getPassword(): string
    {
        return $this->password->getValue();
    }

    public function getRoleName(): string
    {
        return $this->role->getName();
    }

    public function getRoleId(): int
    {
        return $this->role->getId();
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber->getValue();
    }

    public function getId(): ?int
    {
        return $this->id ? $this->id->getValue() : null;
    }

    public function hasPermission(Permission $permission): bool
    {
        return $this->role->hasPermission($permission);
    }
}
