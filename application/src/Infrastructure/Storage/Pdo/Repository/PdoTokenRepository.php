<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Pdo\Repository;

use App\Domain\Entity\BearerToken;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\TokenRepositoryInterface;
use App\Domain\ValueObject\Token\Token;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Storage\Pdo\Connection\PdoConnectionInterface;
use DateTime;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use RuntimeException;

class PdoTokenRepository implements TokenRepositoryInterface
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';
    private PDO $connection;

    public function __construct(PdoConnectionInterface $pdoConnection)
    {
        $this->connection = $pdoConnection->getPDO();
    }

    public function findByToken(Token $token): BearerToken
    {
        $value = $token->getValue();
        $query = "SELECT * FROM tokens WHERE token = :token";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":token", $value);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            throw new EntityNotFoundException(sprintf("Couldn't find user by token: %s", $value));
        }
        return new BearerToken(
            new UserId($row['user_id']),
            new Token($row['token']),
            DateTime::createFromFormat(self::DATE_FORMAT, $row['expiration_date'])
        );
    }

    public function create(BearerToken $bearerToken): BearerToken
    {
        $token = $bearerToken->getToken();
        $userId = $bearerToken->getUserId();
        $expirationDate = $bearerToken->getExpirationDate()->format(self::DATE_FORMAT);

        $query = "INSERT INTO tokens(token, user_id, expiration_date) VALUES (:token, :user_id, :expiration_date)";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':token', $token);
        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindParam(':expiration_date', $expirationDate);
        $statement->execute();

        return clone $bearerToken;
    }
}
