<?php

namespace App\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;

class IntegerValue implements RuleInterface
{
    public function validate(string $fieldName, $fieldValue): void
    {
        if (!$fieldValue || !ctype_digit(strval($fieldValue))) {
            throw new DomainValidationException(
                sprintf("%s should be integer value", ucfirst(strtolower($fieldName)))
            );
        }
    }
}
