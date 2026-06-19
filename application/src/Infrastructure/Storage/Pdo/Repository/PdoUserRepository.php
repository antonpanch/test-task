<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Pdo\Repository;

use App\Domain\Entity\Role;
use App\Domain\Entity\User;
use App\Domain\Exception\DomainValidationException;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Role\RoleId;
use App\Domain\ValueObject\Role\RoleName;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Storage\Pdo\Connection\PdoConnectionInterface;
use PDO;
use PDOException;
use RuntimeException;

class PdoUserRepository implements UserRepositoryInterface
{
    private readonly PDO $connection;

    public function __construct(PdoConnectionInterface $pdoConnection)
    {
        $this->connection = $pdoConnection->getPDO();
    }

    public function create(User $user): User
    {
        $this->checkForDuplicatesOnLoginAndPassword($user);
        $login = $user->getLogin();
        $password = $user->getPassword();
        $phoneNumber = $user->getPhoneNumber();
        $roleId = $user->getRoleId();

        $query = "INSERT INTO users(login, pass, phone, role_id) VALUES (:login, :pass, :phone, :roleId)";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":login", $login);
        $statement->bindParam(":pass", $password);
        $statement->bindParam(":phone", $phoneNumber);
        $statement->bindParam(":roleId", $roleId);

        $statement->execute();
        $id = $this->connection->lastInsertId();
        return new User(
            new Login($login),
            new Password($password),
            new PhoneNumber($phoneNumber),
            new Role(
                new RoleName($user->getRoleName()),
                new RoleId($user->getRoleId())
            ),
            new UserId((int) $id)
        );
    }

    public function findByLoginAndPassword(Login $login, Password $password): User
    {
        $loginValue = $login->getValue();
        $passwordValue = $password->getValue();
        $query = "SELECT u.id AS userId, login, pass, phone, r.id AS roleId, name FROM users u INNER JOIN roles r";
        $query .= " WHERE u.login = :login AND u.pass = :password";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':login', $loginValue);
        $statement->bindParam(':password', $passwordValue);

        $statement->execute();
        $row = $statement->fetch(Pdo::FETCH_ASSOC);
        if ($row === false) {
            throw new EntityNotFoundException("Couldn't find user by login and password");
        }
        return new User(
            new Login($row['login']),
            new Password($row['pass']),
            new PhoneNumber($row['phone']),
            new Role(
                new RoleName($row['name']),
                new RoleId($row['roleId'])
            ),
            new UserId($row['userId'])
        );

    }

    public function findById(UserId $id): User
    {
        $userId = $id->getValue();
        $query = "SELECT u.id AS userId, login, pass, phone, r.id AS roleId, r.name";
        $query .= " FROM users u INNER JOIN roles r ON u.role_id = r.id WHERE u.id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $userId, PDO::PARAM_INT);

        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            throw new EntityNotFoundException(sprintf("Couldn't find user with id: %d", $userId));
        }
        return new User(
            new Login($row['login']),
            new Password($row['pass']),
            new PhoneNumber($row['phone']),
            new Role(
                new RoleName($row['name']),
                new RoleId($row['roleId'])
            ),
            new UserId($row['userId'])
        );

    }

    private function checkForDuplicatesOnLoginAndPassword(User $user): void
    {
        try {
            $this->findByLoginAndPassword(new Login($user->getLogin()), new Password($user->getPassword()));
            throw new DomainValidationException("User with specified login and password already exists");
        } catch (EntityNotFoundException $e) {
            return;
        }
    }



    public function delete(UserId $userId): void
    {
        $id = $userId->getValue();
        $query = "DELETE FROM users WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function update(UserId $userId, Login $login, Password $password, PhoneNumber $phoneNumber): User
    {
        $id = $userId->getValue();
        $loginValue = $login->getValue();
        $passwordValue = $password->getValue();
        $phone = $phoneNumber->getValue();

        $this->connection->beginTransaction();
        try {
            $query = "UPDATE users SET login = :login, pass = :password, phone = :phone WHERE id = :id";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':login', $loginValue);
            $statement->bindParam(':password', $passwordValue);
            $statement->bindParam(':phone', $phone);
            $statement->execute();

            $query = "SELECT r.name, r.id FROM roles AS r INNER JOIN users AS u ON u.role_id = r.id WHERE u.id = :id";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $this->connection->commit();

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            return new User(
                clone $login,
                clone $password,
                clone $phoneNumber,
                new Role(
                    new RoleName($row['name']),
                    new RoleId($row['id'])
                ),
                clone $userId,
            );
        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw new RuntimeException("Couldn't update user");
        }
    }
}
