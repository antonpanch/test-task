<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Token;

use App\Domain\Validation\Rules\StringMaxLength;
use App\Domain\Validation\Rules\StringMinLength;
use App\Domain\Validation\Validator;

class Token
{
    public const TOKEN_MIN_LENGTH = 5;
    public const TOKEN_MAX_LENGTH = 30;

    protected string $value;

    public function __construct(string $value)
    {
        (new Validator())->validate(
            'token',
            $value,
            [
                new StringMinLength(self::TOKEN_MIN_LENGTH),
                new StringMaxLength(self::TOKEN_MAX_LENGTH)
            ]
        );
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
