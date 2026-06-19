<?php

namespace App\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;

class NotEmpty implements RuleInterface
{
    public function validate(string $fieldName, $fieldValue): void
    {
        if (!isset($fieldValue)) {
            throw new DomainValidationException(
                sprintf("%s cannot be empty value", ucfirst(strtolower($fieldName)))
            );
        }
    }
}
