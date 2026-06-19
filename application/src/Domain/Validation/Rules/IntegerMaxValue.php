<?php

namespace App\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;

class IntegerMaxValue implements RuleInterface
{
    public function __construct(private int $maxValue)
    {
    }

    public function validate(string $fieldName, $fieldValue): void
    {
        (new IntegerValue())->validate($fieldName, $fieldValue);
        $intValue = (int) $fieldValue;
        if ($intValue > $this->maxValue) {
            throw new DomainValidationException(
                sprintf(
                    "%s must be an integer value not more, than %d",
                    ucfirst(strtolower($fieldName)),
                    $this->maxValue
                )
            );
        }
    }
}
