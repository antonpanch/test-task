<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use App\Domain\Validation\Rules\StringMaxLength;
use App\Domain\Validation\Rules\StringMinLength;
use App\Domain\Validation\Validator;

class Password
{
    public const PASSWORD_MAX_LENGTH = 8;
    public const PASSWORD_MIN_LENGTH = 5;

    private readonly string $value;

    public function __construct(string $value)
    {
        (new Validator())->validate(
            'password',
            $value,
            [
                new StringMinLength(self::PASSWORD_MIN_LENGTH),
                new StringMaxLength(self::PASSWORD_MAX_LENGTH)
            ]
        );
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
