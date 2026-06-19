<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Token\Token;
use App\Domain\ValueObject\User\UserId;
use DateTimeInterface;

class BearerToken
{
    public function __construct(
        private readonly UserId $userId,
        private readonly Token $token,
        private readonly DateTimeInterface $expirationDate
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId->getValue();
    }

    public function getToken(): string
    {
        return $this->token->getValue();
    }

    public function getExpirationDate(): DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function isExpired(): bool
    {
        return $this->expirationDate->getTimestamp() < time();
    }
}
