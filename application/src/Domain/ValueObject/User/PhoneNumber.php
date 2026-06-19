<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use App\Domain\Validation\Rules\StringMaxLength;
use App\Domain\Validation\Rules\StringMinLength;
use App\Domain\Validation\Validator;

class PhoneNumber
{
    public const PHONE_NUMBER_MIN_LENGTH = 5;
    public const PHONE_NUMBER_MAX_LENGTH = 8;

    private readonly string $value;

    public function __construct(string $value)
    {
        (new Validator())->validate(
            'phone number',
            $value,
            [
                new StringMinLength(self::PHONE_NUMBER_MIN_LENGTH),
                new StringMaxLength(self::PHONE_NUMBER_MAX_LENGTH)
            ]
        );
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
