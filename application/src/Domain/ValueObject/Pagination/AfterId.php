<?php

namespace App\Domain\ValueObject\Pagination;

use App\Domain\Validation\Rules\IntegerMaxValue;
use App\Domain\Validation\Validator;

class AfterId
{
    public const USER_ID_MAX_VALUE = 99999999;
    private $value;

    public function __construct($value)
    {
        $validationChain = [
            new IntegerMaxValue(self::USER_ID_MAX_VALUE)
        ];
        (new Validator())->validate('after id', $value, $validationChain);
        $this->value = (int) $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
