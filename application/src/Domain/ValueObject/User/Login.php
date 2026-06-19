<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use App\Domain\Validation\Rules\StringMaxLength;
use App\Domain\Validation\Rules\StringMinLength;
use App\Domain\Validation\Validator;

class Login
{
    public const LOGIN_MIN_LENGTH = 3;
    public const LOGIN_MAX_LENGTH = 8;

    private readonly string $value;

    public function __construct(string $value)
    {
        (new Validator())->validate(
            'login',
            $value,
            [
                new StringMinLength(self::LOGIN_MIN_LENGTH),
                new StringMaxLength(self::LOGIN_MAX_LENGTH)
            ]
        );
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
