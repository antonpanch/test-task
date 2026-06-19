<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Role;
use App\Domain\ValueObject\Role\RoleName;

interface RoleRepositoryInterface
{
    public function getByName(RoleName $roleName): Role;
}
