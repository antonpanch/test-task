<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Pdo\Repository;

use App\Domain\Entity\Role;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\RoleRepositoryInterface;
use App\Domain\ValueObject\Role\RoleId;
use App\Domain\ValueObject\Role\RoleName;
use App\Infrastructure\Storage\Pdo\Connection\PdoConnectionInterface;
use PDO;

class PdoRoleRepository implements RoleRepositoryInterface
{
    private PDO $connection;

    public function __construct(PdoConnectionInterface $pdoConnection)
    {
        $this->connection = $pdoConnection->getPDO();
    }

    public function getByName(RoleName $roleName): Role
    {
        $name = $roleName->getValue();
        $query = "SELECT * FROM roles WHERE name = :name";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":name", $name);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            throw new EntityNotFoundException(sprintf("Couldn't find role with name: %s", $name));
        }
        return new Role(
            new RoleName($row['name']),
            new RoleId($row['id'])
        );
    }
}
