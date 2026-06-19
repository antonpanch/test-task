<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\BearerToken;
use App\Domain\ValueObject\Token\Token;

interface TokenRepositoryInterface
{
    public function findByToken(Token $token): BearerToken;
    public function create(BearerToken $bearerToken): BearerToken;
}
