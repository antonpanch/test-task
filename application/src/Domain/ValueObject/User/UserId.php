<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use App\Domain\Validation\Rules\IntegerMaxValue;
use App\Domain\Validation\Rules\IntegerMinValue;
use App\Domain\Validation\Rules\IntegerValue;
use App\Domain\Validation\Rules\NotEmpty;
use App\Domain\Validation\Validator;

class UserId
{
    public const USER_ID_MAX_VALUE = 99999999;
    private $value;

    public function __construct($value)
    {
        $validationChain = [
            new NotEmpty(),
            new IntegerValue(),
            new IntegerMinValue(1),
            new IntegerMaxValue(self::USER_ID_MAX_VALUE)
        ];
        (new Validator())->validate('user id', $value, $validationChain);
        $this->value = (int) $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
