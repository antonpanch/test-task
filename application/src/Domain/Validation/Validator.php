<?php

declare(strict_types=1);

namespace App\Domain\Validation;

class Validator
{
    public function validate(string $fieldName, $fieldValue, array $rules): void
    {
        foreach ($rules as $rule) {
            $rule->validate($fieldName, $fieldValue);
        }
    }
}
