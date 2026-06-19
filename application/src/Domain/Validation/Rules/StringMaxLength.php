<?php

declare(strict_types=1);

namespace App\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;

class StringMaxLength implements RuleInterface
{
    public function __construct(private int $maxLength)
    {
    }

    public function validate(string $fieldName, $fieldValue): void
    {
        if (!is_string($fieldValue) || strlen($fieldValue) > $this->maxLength) {
            throw new DomainValidationException(
                sprintf(
                    "%s should be a string and have length not more, than %d",
                    ucfirst(strtolower($fieldName)),
                    $this->maxLength
                )
            );
        }
    }
}
