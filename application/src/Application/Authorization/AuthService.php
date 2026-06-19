<?php

declare(strict_types=1);

namespace App\Application\Authorization;

use App\Domain\Entity\BearerToken;
use App\Domain\Entity\User;
use App\Domain\Exception\PermissionException;
use App\Domain\Repository\TokenRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Token\Token;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\UserId;
use DateTime;

class AuthService
{
    public const TOKEN_VALIDITY_PERIOD_IN_SECONDS = 60 * 60 * 24;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenRepositoryInterface $tokenRepository
    ) {
    }

    public function generateBearerTokenForUser(User $user): BearerToken
    {
        $expirationTimestamp = time() + self::TOKEN_VALIDITY_PERIOD_IN_SECONDS;
        $expirationDate = DateTime::createFromTimestamp($expirationTimestamp);
        $bearerToken = new BearerToken(
            new UserId($user->getId()),
            new Token($this->generateRandomToken()),
            $expirationDate
        );
        return $this->tokenRepository->create($bearerToken);
    }

    public function getUserByToken(string $token): User
    {
        $bearerToken = $this->tokenRepository->findByToken(new Token($token));
        if ($bearerToken->getExpirationDate()->getTimestamp() < time()) {
            throw new PermissionException("Token is expired");
        }
        return $this->userRepository->findById(new UserId($bearerToken->getUserId()));
    }

    private function generateRandomToken(): string
    {
        return substr(
            base64_encode(random_bytes(Token::TOKEN_MAX_LENGTH)),
            0,
            Token::TOKEN_MIN_LENGTH
        );
    }

    public function generateBearerByLoginAndPassword(Login $login, Password $password): BearerToken
    {
        $user = $this->userRepository->findByLoginAndPassword($login, $password);
        return $this->generateBearerTokenForUser($user);
    }
}
