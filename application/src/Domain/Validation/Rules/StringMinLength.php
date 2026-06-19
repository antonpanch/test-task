<?php

declare(strict_types=1);

namespace App\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;

class StringMinLength implements RuleInterface
{
    public function __construct(private int $minLength)
    {
    }

    public function validate(string $fieldName, $fieldValue): void
    {
        if (!is_string($fieldValue) || strlen($fieldValue) < $this->minLength) {
            throw new DomainValidationException(
                sprintf(
                    "%s should be a string and have length not less, than %d",
                    ucfirst(strtolower($fieldName)),
                    $this->minLength
                )
            );
        }
    }
}
