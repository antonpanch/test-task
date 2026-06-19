<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Role;

use App\Domain\Validation\Rules\IntegerMaxValue;
use App\Domain\Validation\Rules\IntegerMinValue;
use App\Domain\Validation\Validator;

class RoleId
{
    public const ROLE_ID_MAX_VALUE = 255;

    private readonly int $value;

    public function __construct(int $value)
    {
        (new Validator())->validate(
            'role id',
            $value,
            [
                new IntegerMinValue(1),
                new IntegerMaxValue(self::ROLE_ID_MAX_VALUE)
            ]
        );
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
