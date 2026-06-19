<?php

namespace App\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;

class IntegerMinValue implements RuleInterface
{
    public function __construct(private int $minValue)
    {
    }

    public function validate(string $fieldName, $fieldValue): void
    {
        (new IntegerValue())->validate($fieldName, $fieldValue);
        $intValue = (int) $fieldValue;
        if ($intValue < $this->minValue) {
            throw new DomainValidationException(
                sprintf(
                    "%s must be an integer value not less, than %d",
                    ucfirst(strtolower($fieldName)),
                    $this->minValue
                )
            );
        }
    }
}
