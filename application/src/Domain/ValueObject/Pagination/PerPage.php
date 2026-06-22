<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Pagination;

use App\Domain\Validation\Rules\IntegerMaxValue;
use App\Domain\Validation\Rules\IntegerMinValue;
use App\Domain\Validation\Rules\IntegerValue;
use App\Domain\Validation\Rules\NotEmpty;
use App\Domain\Validation\Validator;

class PerPage
{
    public const DEFAULT_PER_PAGE = 5;

    public const PER_PAGE_MIN_VALUE = 1;
    public const PER_PAGE_MAX_VALUE = 100;

    private $value;

    public function __construct($value)
    {
        $validationChain = [
            new NotEmpty(),
            new IntegerValue(),
            new IntegerMinValue(self::PER_PAGE_MIN_VALUE),
            new IntegerMaxValue(self::PER_PAGE_MAX_VALUE)
        ];
        (new Validator())->validate('per page', $value, $validationChain);
        $this->value = (int) $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
