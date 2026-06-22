<?php

namespace App\Infrastructure\Api\Formatter;

use App\Domain\Entity\BearerToken;

class TokenFormatter
{
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function formatToken(BearerToken $bearerToken): array
    {
        return [
            'data' => [
                'token' => $bearerToken->getToken(),
                'expirationDatetime' => $bearerToken->getExpirationDate()->format(self::DATETIME_FORMAT)
            ]
        ];
    }
}
